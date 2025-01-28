<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpClassCanBeReadonlyInspection */

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Observer\Wishlist;

use AcidUnit\GoogleTagManager\Model\Wishlist\AddProductData;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Dispatcher for the `wishlist_add_product` event.
 */
class AddProductObserver implements ObserverInterface
{
    /**
     * @param AddProductData $addProductData
     */
    public function __construct(
        private readonly AddProductData $addProductData
    ) {
    }

    /**
     * Handle the `wishlist_add_product` event.
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        $item = $observer->getEvent()->getData('item');
        $this->addProductData->setCustomerData($item);
    }
}
