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
     * @param array<string> $removeKeysList
     * @return array<mixed>
     * @noinspection PhpUndefinedMethodInspection
     * @noinspection PhpCastIsUnnecessaryInspection
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function getProductData(Product $product, array $removeKeysList = []): array
    {
        $result = [];
        $productType = $product->getTypeId();

        $result['id'] = $product->getId();
        $result['sku'] = $product->getSku();
        $result['name'] = trim($product->getName());
        $result['category'] = $this->getCategoryName($product);
        $result['type'] = $productType;

        $price = $product->getFinalPrice();
        $minPrice = (float)$product->getMinimalPrice();
        $maxPrice = (float)$product->getMaxPrice(); // @phpstan-ignore-line

        if ($price) {
            $result['price'] = $price;
        }

        if ($minPrice && $minPrice !== $price) {
            $result['min_price'] = $minPrice;
        }

        if ($maxPrice && $maxPrice !== $price) {
            $result['max_price'] = $maxPrice;
        }

        if ($product->getTypeId() == Configurable::TYPE_CODE) {
            /** @var Configurable $productType */
            $productType = $product->getTypeInstance();
            $childProducts = $productType->getUsedProducts($product);

            foreach ($childProducts as $child) {
                /** @var Product|ProductInterface $child */
                $result['options'][] = $this->getProductData($child, ['category', 'type']);
            }
        }

        foreach ($removeKeysList as $key) {
            if (array_key_exists($key, $result)) {
                unset($result[$key]);
            }
        }

        return $result;
    }
}
