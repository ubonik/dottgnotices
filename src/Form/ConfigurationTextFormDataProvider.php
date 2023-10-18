<?php

declare(strict_types=1);

namespace PrestaShop\Module\Dottgnotices\Form;

use PrestaShop\PrestaShop\Core\Configuration\DataConfigurationInterface;
use PrestaShop\PrestaShop\Core\Form\FormDataProviderInterface;

/**
 * Provider is responsible for providing form data, in this case, it is returned from the configuration component.
 *
 * Class ConfigurationTextFormDataProvider
 */
class ConfigurationTextFormDataProvider implements FormDataProviderInterface
{
    /**
     * @var DataConfigurationInterface
     */
    private $ConfigurationTextDataConfiguration;

    public function __construct(DataConfigurationInterface $ConfigurationTextDataConfiguration)
    {
        $this->ConfigurationTextDataConfiguration = $ConfigurationTextDataConfiguration;
    }

    public function getData(): array
    {
        return $this->ConfigurationTextDataConfiguration->getConfiguration();
    }

    public function setData(array $data): array
    {
        return $this->ConfigurationTextDataConfiguration->updateConfiguration($data);
    }
}
