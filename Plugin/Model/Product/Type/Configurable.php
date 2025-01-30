<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpDeprecationInspection */
/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */
// phpcs:disable Generic.Files.LineLength.TooLong

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Plugin\Model\Product\Type;

use AcidUnit\GoogleTagManager\Model\Config;
use Magento\Catalog\Api\Data\ProductInterfaceFactory;
use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Option;
use Magento\Catalog\Model\Product\Type;
use Magento\Catalog\Model\ResourceModel\Eav\AttributeFactory;
use Magento\ConfigurableProduct\Model\Product\Type\Collection\SalableProcessor;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as ConfigurableTarget;
use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable as ConfigurableProductType;
use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable\Attribute\CollectionFactory as ConfigurableAttributeCollectionFactory;
use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable\Product\CollectionFactory as ConfigurableProductCollectionFactory;
use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\ConfigurableFactory;
use Magento\Customer\Model\Session;
use Magento\Eav\Model\Config as EavModelConfig;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Cache\FrontendInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\File\UploaderFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\MediaStorage\Helper\File\Storage\Database;
use Psr\Log\LoggerInterface;

/**
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Configurable extends ConfigurableTarget
{
    /**
     * @param Config $config
     * @param Option $catalogProductOption
     * @param EavModelConfig $eavConfig
     * @param Type $catalogProductType
     * @param ManagerInterface $eventManager
     * @param Database $fileStorageDb
     * @param Filesystem $filesystem
     * @param Registry $coreRegistry
     * @param LoggerInterface $logger
     * @param ProductRepositoryInterface $productRepository
     * @param ConfigurableFactory $typeConfigurableFactory
     * @param AttributeFactory $eavAttributeFactory
     * @param ConfigurableTarget\AttributeFactory $configurableAttributeFactory
     * @param ConfigurableProductCollectionFactory $productCollectionFactory
     * @param ConfigurableAttributeCollectionFactory $attributeCollectionFactory
     * @param ConfigurableProductType $catalogProductTypeConfigurable
     * @param ScopeConfigInterface $scopeConfig
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param FrontendInterface|null $cache
     * @param Session|null $customerSession
     * @param Json|null $serializer
     * @param ProductInterfaceFactory|null $productFactory
     * @param SalableProcessor|null $salableProcessor
     * @param ProductAttributeRepositoryInterface|null $productAttributeRepository
     * @param SearchCriteriaBuilder|null $searchCriteriaBuilder
     * @param UploaderFactory|null $uploaderFactory
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        private readonly Config                $config,
        Option                                 $catalogProductOption,
        EavModelConfig                         $eavConfig,
        Type                                   $catalogProductType,
        ManagerInterface                       $eventManager,
        Database                               $fileStorageDb,
        Filesystem                             $filesystem,
        Registry                               $coreRegistry,
        LoggerInterface                        $logger,
        ProductRepositoryInterface             $productRepository,
        ConfigurableFactory                    $typeConfigurableFactory,
        AttributeFactory                       $eavAttributeFactory,
        ConfigurableTarget\AttributeFactory    $configurableAttributeFactory,
        ConfigurableProductCollectionFactory   $productCollectionFactory,
        ConfigurableAttributeCollectionFactory $attributeCollectionFactory,
        ConfigurableProductType                $catalogProductTypeConfigurable,
        ScopeConfigInterface                   $scopeConfig,
        JoinProcessorInterface                 $extensionAttributesJoinProcessor,
        FrontendInterface                      $cache = null,
        Session                                $customerSession = null,
        Json                                   $serializer = null,
        ProductInterfaceFactory                $productFactory = null,
        SalableProcessor                       $salableProcessor = null,
        ProductAttributeRepositoryInterface    $productAttributeRepository = null,
        SearchCriteriaBuilder                  $searchCriteriaBuilder = null,
        UploaderFactory                        $uploaderFactory = null
    ) {
        parent::__construct(
            $catalogProductOption,
            $eavConfig,
            $catalogProductType,
            $eventManager,
            $fileStorageDb,
            $filesystem,
            $coreRegistry,
            $logger,
            $productRepository,
            $typeConfigurableFactory,
            $eavAttributeFactory,
            $configurableAttributeFactory,
            $productCollectionFactory,
            $attributeCollectionFactory,
            $catalogProductTypeConfigurable,
            $scopeConfig,
            $extensionAttributesJoinProcessor,
            $cache,
            $customerSession,
            $serializer,
            $productFactory,
            $salableProcessor,
            $productAttributeRepository,
            $searchCriteriaBuilder,
            $uploaderFactory
        );
    }

    /**
     * After Plugin to pass attribute code to product options
     *
     * @param ConfigurableTarget $subject
     * @param array<mixed> $result
     * @param Product $product
     *
     * @return array<mixed>
     * @see \Magento\ConfigurableProduct\Model\Product\Type\Configurable::getSelectedAttributesInfo
     * @noinspection PhpUnusedParameterInspection
     * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetSelectedAttributesInfo(
        ConfigurableTarget $subject,
        array              $result,
        Product            $product
    ): array {
        if (!$this->config->isGtmEnabled()) {
            return $result;
        }

        if ($attributesOption = $product->getCustomOption('attributes')) {
            $data = $attributesOption->getValue();

            if (!$data) {
                return $result;
            }

            $usedAttributes = $product->getData($this->_usedAttributes);

            foreach ($result as &$attribute) {
                $optionId = $attribute['option_id'];
                $code = $usedAttributes[$optionId]->getProductAttribute()->getAttributeCode();
                $attribute['code'] = $code;
            }
        }

        return $result;
    }
}
