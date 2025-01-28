<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpClassCanBeReadonlyInspection */

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Observer\Wishlist;

use AcidUnit\GoogleTagManager\Model\Wishlist\RemoveProductData;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Dispatcher for the `wishlist_remove_product` event.
 */
class RemoveProductObserver implements ObserverInterface
{
    /**
     * @param RemoveProductData $removeProductData
     */
    public function __construct(
        private readonly RemoveProductData $removeProductData
    ) {
    }

    /**
     * Handle the `wishlist_remove_product` event.
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        $item = $observer->getEvent()->getData('item');
        $this->removeProductData->setCustomerData($item);
    }
}
