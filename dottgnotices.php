<?php

declare(strict_types=1);

use PrestaShop\PrestaShop\Adapter\SymfonyContainer;

if (!defined('_PS_VERSION_')) {
    exit;
}

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

class DotTgNotices extends Module
{
    public function __construct()
    {
        $this->name = 'dottgnotices';
        $this->version = '1.0.0';
        $this->author = '';
        $this->ps_versions_compliancy = ['min' => '8.0.0', 'max' => '8.99.99'];
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->trans('Dottgnotices module', [], 'Modules.DotTgNotices.Admin');
    }

    public function getContent()
    {
        $route = $this->get('router')->generate('dottgnotices_config_form');
        Tools::redirectAdmin($route);
    } 
}
