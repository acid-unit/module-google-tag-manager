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
use Magento\Framework\Message\ManagerInterface;

/**
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 */
class RegistrationData
{
    /**
     * @param Session $session
     * @param ManagerInterface $messageManager
     * @param Config $config
     */
    public function __construct(
        private readonly Session          $session,
        private readonly ManagerInterface $messageManager,
        private readonly Config           $config
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
        if (!$this->config->isGtmCustomerSessionRegisterEnabled()) {
            return;
        }

        $customerId = $this->session->getCustomerId();

        if ($customerId) {
            $this->session->setDisposableGtmEventData([ // @phpstan-ignore-line
                'event' => GtmEvents::REGISTRATION_SUCCESSFUL,
                'data' => [
                    'customerId' => $customerId
                ]
            ]);

            return;
        }

        if (!$this->config->isGtmCustomerSessionRegisterFailedEnabled()) {
            return;
        }

        $message = $this->messageManager->getMessages()->getLastAddedMessage();
        $errorMessage = '';

        if ($message) {
            $errorMessage = $message->getText();

            if (!$errorMessage && $message->getIdentifier() === 'customerAlreadyExistsErrorMessage') {
                $errorMessage = __('There is already an account with this email address.');
            }
        }

        $this->session->setDisposableGtmEventData([ // @phpstan-ignore-line
            'event' => GtmEvents::REGISTRATION_FAILED,
            'data' => [
                'message' => $errorMessage
            ]
        ]);
    }
}
