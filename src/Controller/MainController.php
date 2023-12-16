<?php

namespace  PrestaShop\Module\Dottgnotices\Controller;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PrestaShop\Module\Dottgnotices\Service\DotBank;

class MainController extends FrameworkBundleAdminController 
{
    public function authAction(Request $request): Response
    {
        $bank = $this->get('prestashop.module.test.service.bank');
        $authUrl = $bank->authorize(); 

        header("Location: $authUrl");
        exit;

        return $this->render('@Modules/dottgnotices/views/templates/admin/auth.html.twig', [
            'string' => 'test'
        ]);
    }

}