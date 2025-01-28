<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Model;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

class ProductDataProvider
{
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
     * Get category name
     *
     * @param Product $product
     * @return string|null
     */
    private function getCategoryName(Product $product): ?string
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
     * @param array<mixed> $removeKeysList
     * @return array<mixed>
     */
    public function getProductData(Product $product, array $removeKeysList = []): array
    {
        $result = [
            'id' => $product->getId(),
            'name' => trim($product->getName()),
            'sku' => $product->getSku(),
            'price' => $product->getFinalPrice(),
            'category' => $this->getCategoryName($product),
            'type' => $product->getTypeId()
        ];

        foreach ($removeKeysList as $key) {
            if (array_key_exists($key, $result)) {
                unset($result[$key]);
            }
        }

        return $result;
    }

    /**
     * Get configurable product data
     *
     * @param Product|null $product
     * @return array<mixed>
     */
    public function getConfigurableProductData(?Product $product): array
    {
        $result = [];
        $productData = $this->getProductData($product);

        /** @var Configurable $productType */
        $productType = $product->getTypeInstance();
        $childProducts = $productType->getUsedProducts($product);

        foreach ($childProducts as $child) {
            /** @var Product|ProductInterface $child */
            $productData['options'][] = $this->getProductData($child, ['category', 'type']);
        }

        $result[] = $productData;

        return $result;
    }
}
