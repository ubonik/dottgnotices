<?php

use PrestaShop\Module\Dottgnotices\Service\AuthorizationBank;
use PrestaShop\Module\Dottgnotices\Service\WebHook;

class DottgnoticesAfterauthModuleFrontController extends ModuleFrontController
{
    public function initContent()
    { 
        parent::initContent();
      
        $bank = $this->context->controller->getContainer()
        ->get('prestashop.module.dottgnotices.service.authorization_bank');

        $webhook =  $this->context->controller->getContainer()
        ->get('prestashop.module.dottgnotices.service.webhook');
            // Проверяем авторизацию
    if ($bank->checkAuthorization()) {
        echo 'Успешная авторизация!<br>';

        if(!$webhook->checkWebHooks()){
         echo 'Вебхук требуется обновить.<br>Удаляю вебхук...<br>';
           $bank->deleteWebHook();
          echo 'Создаю новый вебхук...<br>';
          $create_wh = $webhook->createWebHook();
          //file_put_contents(__DIR__ . '/afterauth.txt', print_r($create_wh, true));
          echo 'Новый вебхук создан!<br>';
          exit;
        }
        echo 'Вебхук в порядке.<br>';
        exit;
      }

          
        $this->setTemplate('module:dottgnotices/views/templates/front/afterauth.tpl');
        $this->context->link->getModuleLink('dottgnotices', 'afterauth', array());
    }

}
