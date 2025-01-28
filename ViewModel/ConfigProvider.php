<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\ViewModel;

use AcidUnit\Admin\Model\ConfigProviderInterface;
use AcidUnit\GoogleTagManager\Model\Config;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class ConfigProvider implements ArgumentInterface
{
    /**
     * @param Json $serializer
     * @param ConfigProviderInterface $configProvider
     * @param Config $config
     */
    public function __construct(
        private readonly Json                    $serializer,
        private readonly ConfigProviderInterface $configProvider,
        private readonly Config                  $config
    ) {
    }

    /**
     * Get GTM container ID
     *
     * @return string
     */
    public function getContainerId(): string
    {
        return (string)$this->config->getContainerId();
    }

    /**
     * Get GTM config
     *
     * @return string
     */
    public function getSerializedCustomConfig(): string
    {
        return (string)$this->serializer->serialize($this->configProvider->getConfig());
    }
}
