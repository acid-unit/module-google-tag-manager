<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpClassCanBeReadonlyInspection */

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Observer\Account;

use AcidUnit\GoogleTagManager\Model\Customer\LoginData;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Dispatcher for the
 * `controller_action_postdispatch_customer_account_loginPost` and
 * `controller_action_postdispatch_customer_ajax_login` events.
 */
class LoginPostObserver implements ObserverInterface
{
    /**
     * @param LoginData $loginData
     */
    public function __construct(
        private readonly LoginData $loginData
    ) {
    }

    /**
     * Handle the
     * `controller_action_postdispatch_customer_account_loginPost` and
     * `controller_action_postdispatch_customer_ajax_login` events.
     *
     * @param Observer $observer
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(Observer $observer): void
    {
        $this->loginData->setCustomerData();
    }
}
