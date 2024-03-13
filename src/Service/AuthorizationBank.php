<?php

declare(strict_types=1);

namespace PrestaShop\Module\Dottgnotices\Service;

use \Error;
use PrestaShop\PrestaShop\Adapter\Configuration;
use PrestaShop\PrestaShop\Core\ConfigurationInterface;

class AuthorizationBank
{
    private $configuration;
	private $ch; 
	private $apiVersion = 'v2.0';
	private $clientId;
	private $clientSecret;
	private $receiveCodeUrl;	
	private $webhookUrl;	
	private $webhooksList = [
		'incomingPayment',
		'incomingSbpPayment',
	];
	private $scope = 'statements';
	private $configFile = 'config.php';
	private $accessTokensFile = __DIR__ . '/access_tokens.php';
	private $accessToken;
	private $incomingPaymentLog = false;
	private $incomingPaymentLogFile = __DIR__ . 'incoming_payment_log.txt';
	private $incomingPaymentAccounts;
	private $incomingPaymentAmountLimit = 0;
	private $telegramBotToken;
	private $telegramBotTokenChannelId;

	public function __construct(ConfigurationInterface $configuration) {
        $this->configuration = $configuration;
		$this->setConfig();
		$this->ch = curl_init();	
	}

	private function setConfig(): void {
		$this->clientId = $this->configuration->get('DOTTGNOTICES_CLIENT_ID');
		$this->clientSecret = $this->configuration->get('DOTTGNOTICES_CLIENT_SECRET');
		$this->receiveCodeUrl = $this->configuration->get('DOTTGNOTICES_REDIRECT_URL');
		$this->webhookUrl = $this->configuration->get('DOTTGNOTICES_WEBHOOK_URL');
		$this->incomingPaymentAccounts = $this->configuration->get('DOTTGNOTICES_PAYMENT_ACCOUNT');
		$this->telegramBotToken = $this->configuration->get('DOTTGNOTICES_TELEGRAM_BOT_TOKEN');		
		$this->telegramBotTokenChannelId  = $this->configuration->get('DOTTGNOTICES_TELEGRAM_CHANNEL_ID');
	}
	
    public function authorize(): string {
		$authToken = $this->getAuthToken();
		return $this->getRedirectBankAuthorization($authToken);
	}

	 private function getAuthToken(): string {
		$url = 'https://enter.tochka.com/connect/token';
		$data = array(
			'client_id' => $this->clientId,
			'client_secret' => $this->clientSecret,
			'grant_type' => 'client_credentials',
			'scope' => $this->scope,
			'state' => 'qwe'
		);
		$options = array(
			CURLOPT_URL => $url,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => http_build_query($data),
			CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
			CURLOPT_RETURNTRANSFER => true
		);
		curl_setopt_array($this->ch, $options);
		$response = curl_exec($this->ch);
		$data = json_decode($response, true);
		if (isset($data['error']) && !isset($data['access_token'])) {
			throw new Error('Ошибка получения Auth Token');
		}
		return $data['access_token'];
	}

	private function getRedirectBankAuthorization(string $authToken): string {
		$url = 'https://enter.tochka.com/uapi/v1.0/consents';
		$expirationDateTime = date('Y-m-d\TH:i:sP', strtotime('+200 hour'));
		$data = array(
			'Data' => array(
				'permissions' => array(
					'ReadStatements',
				),
				'expirationDateTime' => $expirationDateTime
			)
		);
		$headers = array(
			'Authorization: Bearer ' . $authToken,
			'Content-Type: application/json'
		);
		$options = array(
			CURLOPT_URL => $url,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => json_encode($data),
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_RETURNTRANSFER => true
		);
		curl_setopt_array($this->ch, $options);
		$response = curl_exec($this->ch);
		$data = json_decode($response, true);
		if (
			!isset($data['Data']) ||
			!isset($data['Data']['status']) ||
			$data['Data']['status'] !== 'AwaitingAuthorisation'
		) {
			throw new Error('Ошибка получения Awaiting Authorisation');
		}
		$consentId = $data['Data']['consentId'];	
		$client_id = $this->clientId;
		$response_type = 'code';
		$state = 'Authorization';
		$scope = rawurlencode($this->scope);
		$redirectUrl = $this->receiveCodeUrl;
		return "https://enter.tochka.com/connect/authorize?client_id=$client_id&response_type=$response_type&state=$state&redirect_uri=$redirectUrl&scope=$scope&consent_id=$consentId";
	}
	
}