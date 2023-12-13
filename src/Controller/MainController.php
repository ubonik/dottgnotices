<?php

namespace  PrestaShop\Module\Dottgnotices\Controller;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MainController extends FrameworkBundleAdminController 
{
    public function authAction(Request $request): Response
    {
        dd($request);

        return $this->render('@Modules/dottgnotices/views/templates/admin/auth.html.twig', [
            'string' => 'test',
        ]);
    }

}