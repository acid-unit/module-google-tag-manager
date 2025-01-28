<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */
/** @noinspection PhpUnused */
// phpcs:disable Generic.Files.LineLength.TooLong

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Model;

use AcidUnit\Admin\Model\ConfigProviderInterface;
use Magento\LoginAsCustomerApi\Api\ConfigInterface as LoginAsCustomerConfig;

class GtmConfigProvider implements ConfigProviderInterface
{
    /**
     * @param Config $config
     * @param LoginAsCustomerConfig $loginAsCustomerConfig
     */
    public function __construct(
        private readonly Config                $config,
        private readonly LoginAsCustomerConfig $loginAsCustomerConfig
    ) {
    }

    /**
     * Get GTM config
     *
     * @return array<mixed>
     */
    public function getConfig(): array
    {
        return [
            'enabled' => $this->config->isGtmEnabled(),
            'debug_enabled' => $this->config->isGtmDebugEnabled(),
            'page_load' => [
                'enabled' => $this->config->isGtmPageLoadEnabled(),
                'event_name' => $this->config->getGtmPageLoadEventName(),
                'handles_list_inverted' => $this->config->isGtmPageLoadHandlesListInverted(),
                'handles_list' => $this->config->getGtmPageLoadHandlesList(),
                'pdp_load_event_name' => $this->config->getGtmPageLoadPdpEventName(),
                'plp_load_event_name' => $this->config->getGtmPageLoadPlpEventName()
            ],
            'click' => [
                'product' => [
                    'enabled' => $this->config->isGtmClickProductEnabled(),
                    'event_name' => $this->config->getGtmClickProductEventName()
                ],
                'menu_item' => [
                    'enabled' => $this->config->isGtmClickMenuItemEnabled(),
                    'event_name' => $this->config->getGtmClickMenuItemEventName()
                ],
                'swatch' => [
                    'enabled' => $this->config->isGtmClickSwatchEnabled(),
                    'event_name' => $this->config->getGtmClickSwatchEventName()
                ]
            ],
            'checkout_flow' => [
                'product_added_to_cart' => [
                    'enabled' => $this->config->isGtmCheckoutFlowProductAddedToCartEnabled(),
                    'event_name' => $this->config->getGtmCheckoutFlowProductAddedToCartEventName()
                ],
                'product_removed_from_cart' => [
                    'enabled' => $this->config->isGtmCheckoutFlowProductRemovedFromCartEnabled(),
                    'event_name' => $this->config->getGtmCheckoutFlowProductRemovedFromCartEventName()
                ],
                'cart_item_qty_changed' => [
                    'enabled' => $this->config->isGtmCheckoutFlowCartItemQtyChangedEnabled(),
                    'event_name' => $this->config->getGtmCheckoutFlowCartItemQtyChangedEventName()
                ],
                'checkout_steps_reached' => [
                    'enabled' => $this->config->isGtmCheckoutFlowCheckoutStepsReachedEnabled(),
                    'event_name' => $this->config->getGtmCheckoutFlowCheckoutStepsReachedEventName()
                ],
                'purchase_done' => [
                    'enabled' => $this->config->isGtmCheckoutFlowPurchaseDoneEnabled(),
                    'event_name' => $this->config->getGtmCheckoutFlowPurchaseDoneEventName()
                ]
            ],
            'customer_session' => [
                'login' => [
                    'enabled' => $this->config->isGtmCustomerSessionLoginEnabled(),
                    'event_name' => $this->config->getGtmCustomerSessionLoginEventName(),
                    'failed_enabled' => $this->config->isGtmCustomerSessionLoginFailedEnabled(),
                    'failed_event_name' => $this->config->getGtmCustomerSessionLoginFailedEventName()
                ],
                'logout' => [
                    'enabled' => $this->config->isGtmCustomerSessionLogoutEnabled(),
                    'event_name' => $this->config->getGtmCustomerSessionLogoutEventName()
                ],
                'registration' => [
                    'enabled' => $this->config->isGtmCustomerSessionRegisterEnabled(),
                    'event_name' => $this->config->getGtmCustomerSessionRegisterEventName(),
                    'failed_enabled' => $this->config->isGtmCustomerSessionRegisterFailedEnabled(),
                    'failed_event_name' => $this->config->getGtmCustomerSessionRegisterFailedEventName()
                ]
            ],
            'exposure' => [
                'product' => [
                    'enabled' => $this->config->isGtmExposureProductEnabled(),
                    'event_name' => $this->config->getGtmExposureProductEventName()
                ],
                'menu_category' => [
                    'enabled' => $this->config->isGtmExposureMenuCategoryEnabled(),
                    'event_name' => $this->config->getGtmExposureMenuCategoryEventName()
                ],
                'block' => [
                    'config' => $this->config->getGtmExposureBlockConfigJson(),
                    'event_name' => $this->config->getGtmExposureBlockEventName()
                ]
            ],
            'page_load_events' => [
                'login_success' => $this->config->isGtmCustomerSessionLoginEnabled() ? GtmEvents::LOGIN_SUCCESSFUL : '',
                'login_fail' => $this->config->isGtmCustomerSessionLoginFailedEnabled() ? GtmEvents::LOGIN_FAILED : '',
                'logout_success' => $this->config->isGtmCustomerSessionLogoutEnabled() ? GtmEvents::LOGOUT_SUCCESSFUL : '',
                'registration_success' => $this->config->isGtmCustomerSessionRegisterEnabled() ? GtmEvents::REGISTRATION_SUCCESSFUL : '',
                'registration_fail' => $this->config->isGtmCustomerSessionRegisterFailedEnabled() ? GtmEvents::REGISTRATION_FAILED : '',
                'product_removed_from_cart' => $this->config->isGtmCheckoutFlowProductRemovedFromCartEnabled() ? GtmEvents::PRODUCT_REMOVED_FROM_CART : ''
            ],
            'login_as_customer_enabled' => $this->loginAsCustomerConfig->isEnabled()
        ];
    }
}
