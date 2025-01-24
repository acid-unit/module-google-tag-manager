<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Model;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

class ProductDataStorage
{
    /**
     * @var array
     */
    private array $productData = [];

    /**
     * @var array
     */
    private array $categoryNames = [];

    /**
     * @param CategoryRepositoryInterface $categoryRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository,
        private readonly LoggerInterface             $logger
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

        $this->productData[$productId] = $this->getProductData($product);
    }

    /**
     * Get category name
     *
     * @param Product $product
     * @return string|null
     */
    public function getCategoryName(Product $product): ?string
    {
        $categoryName = $product->getCategory() ? $product->getCategory()->getName() : ''; // @phpstan-ignore-line

        if ($categoryName) {
            return $categoryName;
        }

        $categoryIds = $product->getCategoryIds();

        if (!count($categoryIds)) {
            return '';
        }

        $rootCategoryId = reset($categoryIds);

        if (empty($this->categoryNames[$rootCategoryId])) {
            $category = '';

            try {
                $category = $this->categoryRepository->get($rootCategoryId);
            } catch (NoSuchEntityException $e) {
                $this->logger->critical($e->getMessage());
            }

            $this->categoryNames[$rootCategoryId] = $category->getName();
        }

        return $this->categoryNames[$rootCategoryId];
    }

    /**
     * Get product data
     *
     * @param Product $product
     * @return array<mixed>
     */
    public function getProductData(Product $product): array
    {
        return [
            'name' => trim($product->getName()),
            'sku' => $product->getSku(),
            'price' => $product->getFinalPrice(),
            'category' => $this->getCategoryName($product),
            'type' => $product->getTypeId()
        ];
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
