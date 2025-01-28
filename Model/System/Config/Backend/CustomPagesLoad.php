<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpDeprecationInspection */
/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */
/** @noinspection PhpUnused */

namespace AcidUnit\GoogleTagManager\Model\System\Config\Backend;

use AcidUnit\GoogleTagManager\Helper\AdminCustomPagesLoad;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

/**
 * Backend for serialized array data
 */
class CustomPagesLoad extends Value
{
    /**
     * @param AdminCustomPagesLoad $customPagesLoad
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $config
     * @param TypeListInterface $cacheTypeList
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array<mixed> $data
     */
    public function __construct(
        private readonly AdminCustomPagesLoad $customPagesLoad,
        Context                               $context,
        Registry                              $registry,
        ScopeConfigInterface                  $config,
        TypeListInterface                     $cacheTypeList,
        AbstractResource                      $resource = null,
        AbstractDb                            $resourceCollection = null,
        array                                 $data = []
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
     * @return CustomPagesLoad|$this
     * @noinspection PhpParamsInspection
     */
    protected function _afterLoad(): CustomPagesLoad|static
    {
        $value = $this->getValue();
        $value = $this->customPagesLoad->makeArrayFieldValue($value);
        $this->setValue($value);

        return $this;
    }

    /**
     * Prepare data before save
     *
     * @return $this|CustomPagesLoad
     */
    public function beforeSave(): CustomPagesLoad|static
    {
        $value = $this->getValue();
        $value = $this->customPagesLoad->makeStorableArrayFieldValue($value);
        $this->setValue($value);

        return $this;
    }
}
