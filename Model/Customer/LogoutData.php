<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Model\Customer;

use AcidUnit\GoogleTagManager\Model\Config;
use AcidUnit\GoogleTagManager\Model\GtmEvents;
use Magento\Customer\Model\Session;

/**
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 */
class LogoutData
{
    /**
     * @param Session $session
     * @param Config $config
     */
    public function __construct(
        private readonly Session $session,
        private readonly Config  $config
    ) {
    }

    /**
     * Set customer data
     *
     * @return void
     * @noinspection PhpUndefinedMethodInspection
     */
    public function setCustomerData(): void
    {
        if (!$this->config->isGtmCustomerSessionLogoutEnabled()) {
            return;
        }

        $this->session->setDisposableGtmEventData([ // @phpstan-ignore-line
            'event' => GtmEvents::LOGOUT_SUCCESSFUL
        ]);
    }
}
