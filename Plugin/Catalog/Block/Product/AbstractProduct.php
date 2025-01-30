<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpClassCanBeReadonlyInspection */
/** @noinspection PhpDeprecationInspection */

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Plugin\Catalog\Block\Product;

use AcidUnit\GoogleTagManager\Block\Base;
use AcidUnit\GoogleTagManager\Model\Config;
use AcidUnit\GoogleTagManager\Model\ProductDataStorage;
use Magento\Catalog\Block\Product\AbstractProduct as AbstractProductTarget;
use Magento\Catalog\Model\Product;

class AbstractProduct
{
    /**
     * @param Base $base
     * @param ProductDataStorage $productDataStorage
     * @param Config $config
     */
    public function __construct(
        private readonly Base               $base,
        private readonly ProductDataStorage $productDataStorage,
        private readonly Config             $config,
    ) {
    }

    /**
     * After plugin to add product to the product storage
     *
     * @param AbstractProductTarget $subject
     * @param string $result
     * @param Product $product
     *
     * @return string
     * @see \Magento\Catalog\Block\Product\AbstractProduct::getProductPrice
     * @noinspection UnusedFormalParameterInspection
     * @noinspection PhpUnusedParameterInspection
     * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
     */
    public function afterGetProductPrice(
        AbstractProductTarget $subject,
        string                $result,
        Product               $product
    ): string {
        if (!$this->config->isGtmEnabled()) {
            return $result;
        }

        $this->productDataStorage->addProductToStorage($product);

        return $result . $this->base->getProductIdHtml($product);
    }
}
