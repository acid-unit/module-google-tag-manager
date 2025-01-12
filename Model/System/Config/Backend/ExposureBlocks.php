<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpDeprecationInspection */
/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */
/** @noinspection PhpUnused */

namespace AcidUnit\GoogleTagManager\Model\System\Config\Backend;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use AcidUnit\GoogleTagManager\Helper\AdminExposureBlocks;

/**
 * Backend for serialized array data
 */
class ExposureBlocks extends Value
{
    /**
     * @param AdminExposureBlocks $exposureBlocks
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $config
     * @param TypeListInterface $cacheTypeList
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array<mixed> $data
     */
    public function __construct(
        private readonly AdminExposureBlocks $exposureBlocks,
        Context                              $context,
        Registry                             $registry,
        ScopeConfigInterface                 $config,
        TypeListInterface                    $cacheTypeList,
        AbstractResource                     $resource = null,
        AbstractDb                           $resourceCollection = null,
        array                                $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $config,
            $cacheTypeList,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * Processing after load data
     *
     * @return ExposureBlocks|$this
     * @noinspection PhpParamsInspection
     * @throws LocalizedException
     */
    protected function _afterLoad(): ExposureBlocks|static
    {
        $value = $this->getValue();
        $value = $this->exposureBlocks->makeArrayFieldValue($value);
        $this->setValue($value);

        return $this;
    }

    /**
     * Prepare data before save
     *
     * @return $this|ExposureBlocks
     */
    public function beforeSave(): ExposureBlocks|static
    {
        $value = $this->getValue();
        $value = $this->exposureBlocks->makeStorableArrayFieldValue($value);
        $this->setValue($value);

        return $this;
    }
}
