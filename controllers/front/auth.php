<?php

use PrestaShop\Module\Dottgnotices\Service\DotBank;

class DottgnoticesAuthModuleFrontController extends ModuleFrontController
{
    public function initContent()
    { 
        parent::initContent();

        if (isset($_GET['code'])) {
            $code = $_GET['code'];

            file_put_contents(__DIR__ . '/auth.txt', print_r($code, true));

            $bank = $this->context->controller->getContainer()->get('prestashop.module.dottgnotices.service.bank');
            $redirect = $bank->receiveAuthCode($code);
            header("Location: $redirect");
            exit;
        }
        $this->setTemplate('module:dottgnotices/views/templates/front/auth.tpl');

        $this->context->link->getModuleLink('dottgnotices', 'auth', array());         
    }

}
