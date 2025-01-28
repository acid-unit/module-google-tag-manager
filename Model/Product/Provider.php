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
use AcidUnit\GoogleTagManager\Model\ProductDataProvider;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Psr\Log\LoggerInterface;

class Provider implements DataProviderInterface
{
    /**
     * @var Product|null
     */
    protected ?Product $product = null;

    /**
     * @param ProductDataProvider $productDataProvider
     * @param ProductRepositoryInterface $productRepository
     * @param Configurable $configurableType
     * @param Registry $registry
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly ProductDataProvider        $productDataProvider,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly Configurable               $configurableType,
        private readonly Registry                   $registry,
        private readonly LoggerInterface            $logger
    ) {
    }

    /**
     * Set current product
     *
     * @param Product $product
     * @return void
     */
    public function setProduct(Product $product): void
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
        $product = $this->getProduct();

        if (!$product) {
            return [];
        }

        $parentIds = $this->configurableType->getParentIdsByChild($product->getId());

        if (count($parentIds)) {
            $parentId = reset($parentIds);

            try {
                $product = $this->productRepository->getById($parentId);
            } catch (NoSuchEntityException $e) {
                $this->logger->critical($e->getMessage());
            }
        }

        if ($product->getTypeId() == Configurable::TYPE_CODE) {
            return $this->productDataProvider->getConfigurableProductData($product);
        }

        return $this->productDataProvider->getProductData($product);
    }
}
