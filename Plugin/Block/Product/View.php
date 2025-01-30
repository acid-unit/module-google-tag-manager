<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpClassCanBeReadonlyInspection */

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Plugin\Block\Product;

use AcidUnit\GoogleTagManager\Model\Config;
use AcidUnit\GoogleTagManager\Model\ProductDataStorage;
use Magento\Catalog\Block\Product\View as ViewTarget;
use Magento\Catalog\Model\Product;

class View
{
    /**
     * @param ProductDataStorage $productDataStorage
     * @param Config $config
     */
    public function __construct(
        private readonly ProductDataStorage $productDataStorage,
        private readonly Config             $config
    ) {
    }

    /**
     * After plugin to add product to the product storage
     *
     * @param ViewTarget $subject
     * @param Product $result
     *
     * @return Product
     * @see \Magento\Catalog\Block\Product\View::getProduct
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @noinspection PhpUnusedParameterInspection
     * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
     */
    public function afterGetProduct(
        ViewTarget $subject,
        Product $result
    ): Product {
        if (!$this->config->isGtmEnabled()) {
            return $result;
        }

        $this->productDataStorage->addProductToStorage($result);

        return $result;
    }
}
