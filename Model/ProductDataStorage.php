<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Model;

use Magento\Catalog\Model\Product;

class ProductDataStorage
{
    /**
     * @var array<mixed>
     */
    private array $productData = [];

    /**
     * @param ProductDataProvider $productDataProvider
     */
    public function __construct(
        private readonly ProductDataProvider $productDataProvider
    ) {
    }

    /**
     * Add product to product data storage
     *
     * @param Product $product
     */
    public function addProductToStorage(Product $product): void
    {
        $productId = $product->getId();

        if (array_key_exists($productId, $this->productData)) {
            return;
        }

        $this->productData[$productId] = $this->productDataProvider->getProductData($product);
    }

    /**
     * Get all Products Data
     *
     * @return array<mixed>
     */
    public function getAllProductsData(): array
    {
        return $this->productData;
    }
}
