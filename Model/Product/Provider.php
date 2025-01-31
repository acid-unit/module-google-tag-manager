<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpDeprecationInspection */
/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Model\Product;

use AcidUnit\GoogleTagManager\Api\DataProviderInterface;
use AcidUnit\GoogleTagManager\Model\Config;
use AcidUnit\GoogleTagManager\Model\ProductDataProvider;
use Magento\Catalog\Model\Product;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class Provider implements DataProviderInterface, ArgumentInterface
{
    /**
     * @var Product|null
     */
    protected ?Product $product = null;

    /**
     * @param Config $config
     * @param ProductDataProvider $productDataProvider
     * @param Registry $registry
     * @noinspection DependencyOnImplementationInspection
     */
    public function __construct(
        private readonly Config              $config,
        private readonly ProductDataProvider $productDataProvider,
        private readonly Registry            $registry,
    ) {
    }

    /**
     * Set current product
     *
     * @param Product $product
     * @return void
     */
    protected function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    /**
     * Get Product
     *
     * @return Product|null
     * @noinspection PhpDeprecationInspection
     */
    private function getProduct(): ?Product
    {
        if (!$this->product) {
            $this->product = $this->registry->registry('current_product');
        }

        return $this->product;
    }

    /**
     * Get Data
     *
     * @return array<mixed>
     */
    public function getData(): array
    {
        if (!$this->config->isGtmPageLoadEnabled()) {
            return [];
        }

        $product = $this->getProduct();

        if (!$product) {
            return [];
        }

        if ($product->getTypeId() == Configurable::TYPE_CODE) {
            return [$this->productDataProvider->getConfigurableProductData($product)];
        }

        return [$this->productDataProvider->getProductData($product)];
    }
}
