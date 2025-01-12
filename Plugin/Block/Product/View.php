<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Plugin\Block\Product;

use AcidUnit\GoogleTagManager\Model\ProductDataStorage;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Block\Product\View as ViewTarget;

class View
{
    /**
     * @param ProductDataStorage $productDataStorage
     */
    public function __construct(
        private readonly ProductDataStorage $productDataStorage
    ) {
    }

    /**
     * After plugin
     *
     * @param ViewTarget $subject
     * @param Product $result
     * @return Product
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterGetProduct(
        ViewTarget $subject,
        Product $result
    ): Product {
        $this->productDataStorage->addProductToStorage($result);

        return $result;
    }
}
