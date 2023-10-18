<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */

declare(strict_types=1);

namespace PrestaShop\Module\Dottgnotices\Form;

use PrestaShop\PrestaShop\Core\Configuration\DataConfigurationInterface;
use PrestaShop\PrestaShop\Core\ConfigurationInterface;

/**
 * Configuration is used to save data to configuration table and retrieve from it.
 */
final class ConfigurationTextDataConfiguration implements DataConfigurationInterface
{
    public const DOTTGNOTICES_CLIENT_ID = 'DOTTGNOTICES_CLIENT_ID';
    public const DOTTGNOTICES_CLIENT_SECRET = 'DOTTGNOTICES_CLIENT_SECRET';
    public const DOTTGNOTICES_REDIRECT_URL = 'DOTTGNOTICES_REDIRECT_URL';
    public const DOTTGNOTICES_WEBHOOK_URL = 'DOTTGNOTICES_WEBHOOK_URL';
    public const DOTTGNOTICES_PAYMENT_ACCOUNT = 'DOTTGNOTICES_PAYMENT_ACCOUNT';
    public const DOTTGNOTICES_TELEGRAM_BOT_TOKEN = 'DOTTGNOTICES_TELEGRAM_BOT_TOKEN';
    public const DOTTGNOTICES_TELEGRAM_CHANNEL_ID = 'DOTTGNOTICES_TELEGRAM_CHANNEL_ID';    

    /**
     * @var ConfigurationInterface
     */
    private $configuration;

    public function __construct(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    public function getConfiguration(): array
    {
        $return = [];

        $return['client_id'] = $this->configuration->get(static::DOTTGNOTICES_CLIENT_ID);
        $return['client_secret'] = $this->configuration->get(static::DOTTGNOTICES_CLIENT_SECRET);
        $return['redirect_url'] = $this->configuration->get(static::DOTTGNOTICES_REDIRECT_URL);
        $return['webhook_url'] = $this->configuration->get(static::DOTTGNOTICES_WEBHOOK_URL);
        $return['payment_account'] = $this->configuration->get(static::DOTTGNOTICES_PAYMENT_ACCOUNT);
        $return['telegram_bot_token'] = $this->configuration->get(static::DOTTGNOTICES_TELEGRAM_BOT_TOKEN);
        $return['telegram_channel_id'] = $this->configuration->get(static::DOTTGNOTICES_TELEGRAM_CHANNEL_ID);        

        return $return;
    }

    public function updateConfiguration(array $configuration): array
    {
        $errors = [];

        $this->configuration->set(static::DOTTGNOTICES_CLIENT_ID, $configuration['client_id']);
        $this->configuration->set(static::DOTTGNOTICES_CLIENT_SECRET, $configuration['client_secret']);
        $this->configuration->set(static::DOTTGNOTICES_REDIRECT_URL, $configuration['redirect_url']);
        $this->configuration->set(static::DOTTGNOTICES_WEBHOOK_URL, $configuration['webhook_url']);
        $this->configuration->set(static::DOTTGNOTICES_PAYMENT_ACCOUNT, $configuration['payment_account']);
        $this->configuration->set(static::DOTTGNOTICES_TELEGRAM_BOT_TOKEN, $configuration['telegram_bot_token']);
        $this->configuration->set(static::DOTTGNOTICES_TELEGRAM_CHANNEL_ID, $configuration['telegram_channel_id']);

        /* Errors are returned here. */
        return $errors;
    }

    /**
     * Ensure the parameters passed are valid.
     *
     * @return bool Returns true if no exception are thrown
     */
    public function validateConfiguration(array $configuration): bool
    {
        return true;
    }
}
