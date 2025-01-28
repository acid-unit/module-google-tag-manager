<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpClassCanBeReadonlyInspection */

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Observer\Account;

use AcidUnit\GoogleTagManager\Model\Customer\RegistrationData;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Dispatcher for the `controller_action_postdispatch_customer_account_createPost` event.
 */
class CreatePostObserver implements ObserverInterface
{
    /**
     * @param RegistrationData $registrationData
     */
    public function __construct(
        private readonly RegistrationData $registrationData
    ) {
    }

    /**
     * Handle the `controller_action_postdispatch_customer_account_createPost` event.
     *
     * @param Observer $observer
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(Observer $observer): void
    {
        $this->registrationData->setCustomerData();
    }
}
