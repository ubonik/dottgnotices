<?php

use PrestaShop\Module\Dottgnotices\Service\ReceivingIncomingMessages;

class DottgnoticesIncomingpaymentModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        $postData = file_get_contents('php://input');    

		    if ($postData != null) {		
		        file_put_contents(__DIR__ . '/incoming2.txt', print_r($postData, true));       
            $bank = $this->context->controller->getContainer()->get('prestashop.module.dottgnotices.service.receiving_incoming_messages');
            $bank->getIncomingMessage($postData);
		    }  
        $this->setTemplate('module:dottgnotices/views/templates/front/incomingpayment.tpl');

        Context::getContext()->link->getModuleLink('dottgnotices', 'incomingpayment', array());
    }
}
