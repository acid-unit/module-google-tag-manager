<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpDeprecationInspection */

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Model\Product;

use AcidUnit\Core\Api\DataProviderInterface;
use AcidUnit\GoogleTagManager\Model\ProductDataProvider;
use Magento\Catalog\Model\Product;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class Provider implements DataProviderInterface, ArgumentInterface
{
    /**
     * @var Product|null
     */
    protected ?Product $product = null;

    /**
     * @param ProductDataProvider $productDataProvider
     * @param Registry $registry
     */
    public function __construct(
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
     * @return array<array>
     */
    public function getData(): array
    {
        $product = $this->getProduct();

        if (!$product) {
            return [];
        }

        return [$this->productDataProvider->getProductData($product)];
    }
}
