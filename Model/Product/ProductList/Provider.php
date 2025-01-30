<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpDeprecationInspection */
/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */
// phpcs:disable Magento2.Performance.ForeachArrayMerge.ForeachArrayMerge

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Model\Product\ProductList;

use AcidUnit\GoogleTagManager\Api\DataProviderInterface;
use AcidUnit\GoogleTagManager\Model\Config;
use AcidUnit\GoogleTagManager\Model\ProductDataProvider;
use AcidUnit\GoogleTagManager\Model\Product\Provider as ProductProvider;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Resolver as LayerResolver;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Registry;
use Psr\Log\LoggerInterface;

class Provider extends ProductProvider implements DataProviderInterface
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
     * @param Config $config
     * @param ProductDataProvider $productDataProvider
     * @param ProductRepositoryInterface $productRepository
     * @param Configurable $configurableType
     * @param Registry $registry
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly LayerResolver $layerResolver,
        private readonly Config        $config,
        ProductDataProvider            $productDataProvider,
        ProductRepositoryInterface     $productRepository,
        Configurable                   $configurableType,
        Registry                       $registry,
        LoggerInterface                $logger
    ) {
        parent::__construct(
            $config,
            $productDataProvider,
            $productRepository,
            $configurableType,
            $registry,
            $logger
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
     * @return array<mixed>
     */
    public function getData(): array
    {
        $data = [];

        if (!$this->config->isGtmPageLoadEnabled()) {
            return $data;
        }

        foreach ($this->getProductCollection() as $product) {
            $this->setProduct($product);
            $data = array_merge($data, parent::getData());
        }

        return $data;
    }
}
