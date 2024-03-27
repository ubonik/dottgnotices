<?php

namespace  PrestaShop\Module\Dottgnotices\Controller;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PrestaShop\Module\Dottgnotices\Service\AuthorizationBank;

class MainController extends FrameworkBundleAdminController 
{
    public function authAction(Request $request): Response
    {
        $bank = $this->get('prestashop.module.dottgnotices.service.authorization_bank');
        $authUrl = $bank->authorize();
 
        return $this->redirect($authUrl);      
    }

    public function createWebHook(Request $request): Response
    {
        $bank = $this->get('prestashop.module.dottgnotices.service.authorization_bank');
        
        $webhook = '';
        if ($bank->checkAuthorization()) {
            $authorization = 'Вы авторизованы';

            if (!$bank->checkWebHooks()) {
                $bank->deleteWebHook();
                $bank->createWebHook();
                $webhook = 'Вебхук обновлен';
            } else {
                $webhook = 'Вебхук не нуждается в обновлении.';
            } 
        } else {
            $authorization = 'Требуется авторизация!!!';
        }

        return $this->render('@Modules/dottgnotices/views/templates/admin/auth.html.twig', [
            'autorization' => $success_authorization,
            'webhook' => $webhook,
        ]);
        
    }
    
}