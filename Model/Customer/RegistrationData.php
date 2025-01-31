<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpClassCanBeReadonlyInspection */

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Model\Customer;

use AcidUnit\GoogleTagManager\Model\Config;
use AcidUnit\GoogleTagManager\Model\DisposableEvents;
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
     * @noinspection DependencyOnImplementationInspection
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
                'event' => DisposableEvents::REGISTRATION_SUCCESSFUL,
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
                // same message as in vendor/magento/module-customer/view/frontend/templates/messages/customerAlreadyExistsErrorMessage.phtml
                $errorMessage = __('There is already an account with this email address.');
            }
        }

        $this->session->setDisposableGtmEventData([ // @phpstan-ignore-line
            'event' => DisposableEvents::REGISTRATION_FAILED,
            'data' => [
                'message' => $errorMessage
            ]
        ]);
    }
}
