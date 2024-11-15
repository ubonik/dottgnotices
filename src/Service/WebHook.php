<?php

declare(strict_types=1);

namespace PrestaShop\Module\Dottgnotices\Service;

use \Error;
use PrestaShop\PrestaShop\Adapter\Configuration;
use PrestaShop\PrestaShop\Core\ConfigurationInterface;

class WebHook
{
	private $ch; 
	private $clientId;
	private $apiVersion = 'v2.0';
	private $domain;
	private $webhookUrl = "/module/dottgnotices/incomingpayment";
	private $webhooksList = [
		'incomingPayment',
		'incomingSbpPayment',
	];
	private $accessToken;

	public function __construct(ConfigurationInterface $configuration) {
        $this->configuration = $configuration;
		$this->setConfig();
		$this->ch = curl_init();	
	}

	private function setConfig(): void {
		$this->domain = $this->configuration->get('DOTTGNOTICES_DOMAIN');
		$this->clientId = $this->configuration->get('DOTTGNOTICES_CLIENT_ID');
		$this->incomingPaymentAccounts = $this->configuration->get('DOTTGNOTICES_PAYMENT_ACCOUNT');
	}

    public function createWebHook(): bool {
		if ($this->checkWebHooks()) {
			return false;
		}
		$url = 'https://enter.tochka.com/uapi/webhook/' . $this->apiVersion . '/' . $this->clientId;
		$authorizationToken = 'Bearer ' . $this->accessToken;
		$data = [
			'webhooksList' => $this->webhooksList,
			'url' => $this->domain . $this->webhookUrl,
		];
		$jsonData = json_encode($data);
		$options = array(
			CURLOPT_URL => $url,
			CURLOPT_CUSTOMREQUEST => 'PUT',
			CURLOPT_POSTFIELDS => $jsonData,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => [
				'Authorization: ' . $authorizationToken,
				'Content-Type: application/json'
			],
		);
		curl_setopt_array($this->ch, $options);
		$response = curl_exec($this->ch);
		$data = json_decode($response, true);
		if (isset($data['Data']) && isset($data['Data']['webhooksList'])) {
			$ResponsesList = $data['Data']['webhooksList'];
			$incoming = count($this->webhooksList);
			foreach ($this->webhooksList as $webhook) {
				if (in_array($webhook, $ResponsesList)) {
					$incoming--;
				}
			}
			return $incoming == 0;
		}
		return false;
	}

	public function deleteWebHook(): bool {
		$url = 'https://enter.tochka.com/uapi/webhook/' . $this->apiVersion . '/' . $this->clientId;
		$authorizationToken = 'Bearer ' . $this->accessToken;
		$options = array(
			CURLOPT_URL => $url,
			CURLOPT_CUSTOMREQUEST => 'DELETE',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => [
				'Authorization: ' . $authorizationToken,
			],
		);
		curl_setopt_array($this->ch, $options);
		$response = curl_exec($this->ch);
		$data = json_decode($response, true);
		if (isset($data['Data']) && isset($data['Data']['result'])) {
			return $data['Data']['result'] == '1';
		}
		return false;
	}

    public function checkWebHooks(): bool {
		$url = 'https://enter.tochka.com/uapi/webhook/' . $this->apiVersion . '/' . $this->clientId;
		$authorizationToken = 'Bearer ' . $this->accessToken;
		$options = array(
			CURLOPT_URL => $url,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => [
				'Authorization: ' . $authorizationToken,
			],
		);
		curl_setopt_array($this->ch, $options);
		$response = curl_exec($this->ch);
		$data = json_decode($response, true);
		if (isset($data['Data']) && isset($data['Data']['webhooksList']) && isset($data['Data']['url'])) {
			$ResponsesList = $data['Data']['webhooksList'];
			$incoming = count($this->webhooksList);
			foreach ($this->webhooksList as $webhook) {
				if (in_array($webhook, $ResponsesList)) {
					$incoming--;
				}
			}
			return $incoming == 0 && $data['Data']['url'] == $this->domain . $this->webhookUrl;
		}
		return false;
	}
}