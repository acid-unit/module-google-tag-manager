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
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * Add product to the product data storage
     *
     * @param Product $product
     */
    public function addProductToStorage(Product $product): void
    {
        $productId = $product->getId();

        if (array_key_exists($productId, $this->productData)) {
            return;
        }

        $this->productData[$productId] = $this->mapProductData($product);
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
     * Map product data
     *
     * TODO: rename
     *
     * @param Product $product
     * @return array<mixed>
     */
    public function mapProductData(Product $product): array
    {
        return [
            'name' => $product->getName(),
            'id' => $product->getSku(),
            'price' => $product->getFinalPrice(),
            'category' => $this->getCategoryName($product)
        ];
    }

    /**
     * Get Product Data
     *
     * @return array
     */
    public function getProductData(): array
    {
        return $this->productData;
    }
}
