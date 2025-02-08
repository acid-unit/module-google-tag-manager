<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpDeprecationInspection */
// phpcs:disable Magento2.Performance.ForeachArrayMerge.ForeachArrayMerge

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Model\Product\ProductList;

use AcidUnit\Core\Api\DataProviderInterface;
use AcidUnit\GoogleTagManager\Model\Product\Provider as ProductProvider;
use AcidUnit\GoogleTagManager\Model\ProductDataProvider;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Resolver as LayerResolver;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class Provider extends ProductProvider implements DataProviderInterface, ArgumentInterface
{
    /**
     * @var Layer|null
     */
    private ?Layer $layer = null;

    /**
     * @var Collection|null
     */
    private ?Collection $productCollection = null;

    /**
     * @param LayerResolver $layerResolver
     * @param ProductDataProvider $productDataProvider
     * @param Registry $registry
     */
    public function __construct(
        private readonly LayerResolver $layerResolver,
        ProductDataProvider            $productDataProvider,
        Registry                       $registry
    ) {
        parent::__construct(
            $productDataProvider,
            $registry
        );
    }

    /**
     * Get product collection
     *
     * @return Collection
     */
    private function getProductCollection(): Collection
    {
        if (!$this->productCollection) {
            $this->productCollection = $this->getLayer()->getProductCollection();
        }

        return $this->productCollection;
    }

    /**
     * Get layer
     *
     * @return Layer
     */
    private function getLayer(): Layer
    {
        if (!$this->layer) {
            $this->layer = $this->layerResolver->get();
        }

        return $this->layer;
    }

    /**
     * Get Data
     *
     * @return array<array>
     */
    public function getData(): array
    {
        $data = [];

        foreach ($this->getProductCollection() as $product) {
            $this->setProduct($product);
            $data = array_merge($data, parent::getData());
        }

        return $data;
    }
}
