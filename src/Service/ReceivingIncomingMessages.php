<?php

declare(strict_types=1);

namespace PrestaShop\Module\Dottgnotices\Service;

use PrestaShop\PrestaShop\Adapter\Configuration;
use PrestaShop\PrestaShop\Core\ConfigurationInterface;

use \Error;

class ReceivingIncomingMessages
{
    private $configuration;
	private $ch;
	private $incomingPaymentLog = true;
	private $incomingPaymentLogFile = __DIR__ .'dottgnotices_log.txt';
	private $telegramBotToken;
	private $telegramBotTokenChannelId;	
	//private $incomingPaymentAmountLimit = 0;



	public function __construct(ConfigurationInterface $configuration) {
		$this->ch = curl_init();
        $this->configuration = $configuration;		
		$this->setConfig();
	}

	/**
	 * @return void
	 */	
	private function setConfig(): void {
		//$this->incomingPaymentAccounts[] = $this->configuration->get('DOTTGNOTICES_PAYMENT_ACCOUNT');
		$this->telegramBotToken = $this->configuration->get('DOTTGNOTICES_TELEGRAM_BOT_TOKEN');		
		$this->telegramBotTokenChannelId  = $this->configuration->get('DOTTGNOTICES_TELEGRAM_CHANNEL_ID');
	}

	public function getIncomingMessage(string $jwt): void {
		$data = $this->decodeJWT($jwt);
		
        file_put_contents(__DIR__ . '/message.txt', print_r($data, true));

		if (!isset($data['SidePayer']) || !isset($data['SideRecipient'])) {
			throw new Error('Ошибка обработки уведомления о платеже');
		}
		$payerName = $data['SidePayer']['name'];
		$info = $data['SideRecipient'];
		$amount = $info['amount'];		
		$currency = $info['currency'] == 'RUB' ? '₽' : ' ' . $info['currency'];
		$comment = $data['purpose'] ?? '';			
		$message = "$payerName\r\n".'<b>' . $amount . "</b>$currency \r\n$comment";
		$this->telegramBotSendMessage($message);
		$this->incomingPaymentLoging($jwt, $message);
	}

	/**
	  * @param $jwt string
	 * @return array
	 */
	private function decodeJWT(string $jwt): ?array {
		$parts = explode('.', $jwt);
		if(isset($parts[1])) {
		    return json_decode(base64_decode($parts[1]), true);
	    }
	}

	private function incomingPaymentLoging(string $jwt, string $message):void {
		if($this->incomingPaymentLog){
			$file = $this->incomingPaymentLogFile;
			$date = date('Y-m-d H-i-s');
			$text = "\r\n".$date."\r\n".$jwt."\r\n";
			if($message){
				$text .= "$message\r\n";
			}
			file_put_contents($file, $text, FILE_APPEND);
		}
	}

	/**
	 * @param string $message 
	 */
	private function telegramBotSendMessage(string $message): void {
		if (!$this->telegramBotToken || !$this->telegramBotTokenChannelId) {
			return;
		}
		$url = 'https://api.telegram.org/bot' . $this->telegramBotToken . '/sendMessage';
		$data = array(
			'chat_id' => $this->telegramBotTokenChannelId,
			'text' => $message,
			'parse_mode' => 'HTML',
			'disable_notification' => false,
		);
		$options = array(
			CURLOPT_URL => $url,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $data,
			CURLOPT_RETURNTRANSFER => true
		);
		curl_setopt_array($this->ch, $options);
		curl_exec($this->ch);
	}
}