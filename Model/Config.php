<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\ScopeInterface;

class Config extends DataObject implements ArgumentInterface
{
    // General
    public const XML_PATH_GTM_ENABLED
        = 'google/google_tag_manager/active';
    public const XML_PATH_GTM_CONTAINER_ID
        = 'google/google_tag_manager/container_id';
    public const XML_PATH_GTM_DEBUG_ENABLED
        = 'google/google_tag_manager/debug_enabled';

    // Page Load
    public const XML_PATH_GTM_PAGE_LOAD_ENABLED
        = 'google/google_tag_manager/page_load/active';
    public const XML_PATH_GTM_PAGE_LOAD_EVENT_NAME
        = 'google/google_tag_manager/page_load/event_name';
    public const XML_PATH_GTM_PAGE_LOAD_HANDLES_LIST_BEHAVIOR
        = 'google/google_tag_manager/page_load/handles_list_behavior';
    public const XML_PATH_GTM_PAGE_LOAD_HANDLES_LIST
        = 'google/google_tag_manager/page_load/handles_list';

    // Click
    public const XML_PATH_GTM_CLICK_PRODUCT_ENABLED
        = 'google/google_tag_manager/click/product/active';
    public const XML_PATH_GTM_CLICK_PRODUCT_EVENT_NAME
        = 'google/google_tag_manager/click/product/event_name';
    public const XML_PATH_GTM_CLICK_MENU_ITEM_ENABLED
        = 'google/google_tag_manager/click/menu_item/active';
    public const XML_PATH_GTM_CLICK_MENU_ITEM_EVENT_NAME
        = 'google/google_tag_manager/click/menu_item/event_name';
    public const XML_PATH_GTM_CLICK_SWATCH_ENABLED
        = 'google/google_tag_manager/click/swatch/active';
    public const XML_PATH_GTM_CLICK_SWATCH_EVENT_NAME
        = 'google/google_tag_manager/click/swatch/event_name';

    // Checkout Flow
    public const XML_PATH_GTM_CHECKOUT_FLOW_PRODUCT_ADDED_TO_CART_ENABLED
        = 'google/google_tag_manager/checkout_flow/add_to_cart/active';
    public const XML_PATH_GTM_CHECKOUT_FLOW_PRODUCT_ADDED_TO_CART_EVENT_NAME
        = 'google/google_tag_manager/checkout_flow/add_to_cart/event_name';
    public const XML_PATH_GTM_CHECKOUT_FLOW_PRODUCT_REMOVED_FROM_CART_ENABLED
        = 'google/google_tag_manager/checkout_flow/remove_from_cart/active';
    public const XML_PATH_GTM_CHECKOUT_FLOW_PRODUCT_REMOVED_FROM_CART_EVENT_NAME
        = 'google/google_tag_manager/checkout_flow/remove_from_cart/event_name';
    public const XML_PATH_GTM_CHECKOUT_FLOW_CART_ITEM_QTY_CHANGED_ENABLED
        = 'google/google_tag_manager/checkout_flow/cart_item_qty_changed/active';
    public const XML_PATH_GTM_CHECKOUT_FLOW_CART_ITEM_QTY_CHANGED_EVENT_NAME
        = 'google/google_tag_manager/checkout_flow/cart_item_qty_changed/event_name';
    public const XML_PATH_GTM_CHECKOUT_FLOW_CART_PAGE_LOADED_ENABLED
        = 'google/google_tag_manager/checkout_flow/cart_page_loaded/active';
    public const XML_PATH_GTM_CHECKOUT_FLOW_CART_PAGE_LOADED_EVENT_NAME
        = 'google/google_tag_manager/checkout_flow/cart_page_loaded/event_name';
    public const XML_PATH_GTM_CHECKOUT_FLOW_CHECKOUT_STEPS_REACHED_ENABLED
        = 'google/google_tag_manager/checkout_flow/checkout_steps_reached/active';
    public const XML_PATH_GTM_CHECKOUT_FLOW_CHECKOUT_STEPS_REACHED_EVENT_NAME
        = 'google/google_tag_manager/checkout_flow/checkout_steps_reached/event_name';
    public const XML_PATH_GTM_CHECKOUT_FLOW_PURCHASE_DONE_ENABLED
        = 'google/google_tag_manager/checkout_flow/purchase_done/active';
    public const XML_PATH_GTM_CHECKOUT_FLOW_PURCHASE_DONE_EVENT_NAME
        = 'google/google_tag_manager/checkout_flow/purchase_done/event_name';

    // Customer Session
    public const XML_PATH_GTM_CUSTOMER_SESSION_LOGIN_ENABLED
        = 'google/google_tag_manager/customer_session/login/active';
    public const XML_PATH_GTM_CUSTOMER_SESSION_LOGIN_EVENT_NAME
        = 'google/google_tag_manager/customer_session/login/event_name';
    public const XML_PATH_GTM_CUSTOMER_SESSION_LOGIN_FAILED_ENABLED
        = 'google/google_tag_manager/customer_session/login/failed_active';
    public const XML_PATH_GTM_CUSTOMER_SESSION_LOGIN_FAILED_EVENT_NAME
        = 'google/google_tag_manager/customer_session/login/failed_event_name';
    public const XML_PATH_GTM_CUSTOMER_SESSION_LOGOUT_ENABLED
        = 'google/google_tag_manager/customer_session/logout/active';
    public const XML_PATH_GTM_CUSTOMER_SESSION_LOGOUT_EVENT_NAME
        = 'google/google_tag_manager/customer_session/logout/event_name';
    public const XML_PATH_GTM_CUSTOMER_SESSION_REGISTER_ENABLED
        = 'google/google_tag_manager/customer_session/register/active';
    public const XML_PATH_GTM_CUSTOMER_SESSION_REGISTER_EVENT_NAME
        = 'google/google_tag_manager/customer_session/register/event_name';
    public const XML_PATH_GTM_CUSTOMER_SESSION_REGISTER_FAILED_ENABLED
        = 'google/google_tag_manager/customer_session/register/failed_active';
    public const XML_PATH_GTM_CUSTOMER_SESSION_REGISTER_FAILED_EVENT_NAME
        = 'google/google_tag_manager/customer_session/register/failed_event_name';

    // Exposure
    public const XML_PATH_GTM_EXPOSURE_PRODUCT_ENABLED
        = 'google/google_tag_manager/exposure/product/active';
    public const XML_PATH_GTM_EXPOSURE_PRODUCT_EVENT_NAME
        = 'google/google_tag_manager/exposure/product/event_name';
    public const XML_PATH_GTM_EXPOSURE_MENU_CATEGORY_ENABLED
        = 'google/google_tag_manager/exposure/menu_category/active';
    public const XML_PATH_GTM_EXPOSURE_MENU_CATEGORY_EVENT_NAME
        = 'google/google_tag_manager/exposure/menu_category/event_name';
    public const XML_PATH_GTM_EXPOSURE_BLOCK
        = 'google/google_tag_manager/exposure/block/block';
    public const XML_PATH_GTM_EXPOSURE_BLOCK_EVENT_NAME
        = 'google/google_tag_manager/exposure/block/event_name';

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param array<mixed> $data
     */
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig,
        array                                 $data = []
    ) {
        parent::__construct($data);
    }

    /**
     * Get GTM Block Exposure Event Name
     *
     * @return string
     */
    public function getGtmExposureBlockEventName(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_GTM_EXPOSURE_BLOCK_EVENT_NAME,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get GTM Block Exposure JSON config
     *
     * @return string
     */
    public function getGtmExposureBlockConfigJson(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_GTM_EXPOSURE_BLOCK,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get GTM Menu Category Exposure Event Name
     *
     * @return string
     */
    public function getGtmExposureMenuCategoryEventName(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_GTM_EXPOSURE_MENU_CATEGORY_EVENT_NAME,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is GTM Menu Category Exposure enabled
     *
     * @return bool
     */
    public function isGtmExposureMenuCategoryEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_GTM_EXPOSURE_MENU_CATEGORY_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get GTM Product Exposure Event Name
     *
     * @return string
     */
    public function getGtmExposureProductEventName(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_GTM_EXPOSURE_PRODUCT_EVENT_NAME,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is GTM Product Exposure enabled
     *
     * @return bool
     */
    public function isGtmExposureProductEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_GTM_EXPOSURE_PRODUCT_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get GTM Customer Session Register Failed Event Name
     *
     * @return string
     */
    public function getGtmCustomerSessionRegisterFailedEventName(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_GTM_CUSTOMER_SESSION_REGISTER_FAILED_EVENT_NAME,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is GTM Customer Session Register Failed enabled
     *
     * @return bool
     */
    public function isGtmCustomerSessionRegisterFailedEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_GTM_CUSTOMER_SESSION_REGISTER_FAILED_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get GTM Customer Session Register Event Name
     *
     * @return string
     */
    public function getGtmCustomerSessionRegisterEventName(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_GTM_CUSTOMER_SESSION_REGISTER_EVENT_NAME,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is GTM Customer Session Register enabled
     *
     * @return bool
     */
    public function isGtmCustomerSessionRegisterEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_GTM_CUSTOMER_SESSION_REGISTER_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get GTM Customer Session Logout Event Name
     *
     * @return string
     */
    public function getGtmCustomerSessionLogoutEventName(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_GTM_CUSTOMER_SESSION_LOGOUT_EVENT_NAME,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is GTM Customer Session Logout enabled
     *
     * @return bool
     */
    public function isGtmCustomerSessionLogoutEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_GTM_CUSTOMER_SESSION_LOGOUT_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get GTM Customer Session Login Failed Event Name
     *
     * @return string
     */
    public function getGtmCustomerSessionLoginFailedEventName(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_GTM_CUSTOMER_SESSION_LOGIN_FAILED_EVENT_NAME,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is GTM Customer Session Login Failed enabled
     *
     * @return bool
     */
    public function isGtmCustomerSessionLoginFailedEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_GTM_CUSTOMER_SESSION_LOGIN_FAILED_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get GTM Customer Session Login Event Name
     *
     * @return string
     */
    public function getGtmCustomerSessionLoginEventName(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_GTM_CUSTOMER_SESSION_LOGIN_EVENT_NAME,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is GTM Customer Session Login enabled
     *
     * @return bool
     */
    public function isGtmCustomerSessionLoginEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_GTM_CUSTOMER_SESSION_LOGIN_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get GTM Checkout Flow Purchase Done Event Name
     *
     * @return string
     */
    public function getGtmCheckoutFlowPurchaseDoneEventName(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_GTM_CHECKOUT_FLOW_PURCHASE_DONE_EVENT_NAME,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is GTM Checkout Flow Purchase Done Enabled
     *
     * @return bool
     */
    public function isGtmCheckoutFlowPurchaseDoneEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_GTM_CHECKOUT_FLOW_PURCHASE_DONE_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get GTM Checkout Flow Checkout Step Reached Event Name
     *
     * @return string
     */
    public function getGtmCheckoutFlowCheckoutStepsReachedEventName(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_GTM_CHECKOUT_FLOW_CHECKOUT_STEPS_REACHED_EVENT_NAME,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is GTM Checkout Flow Checkout Step Reached Enabled
     *
     * @return bool
     */
    public function isGtmCheckoutFlowCheckoutStepsReachedEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_GTM_CHECKOUT_FLOW_CHECKOUT_STEPS_REACHED_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get GTM Checkout Flow Cart Page Loaded Event Name
     *
     * @return string
     */
    public function getGtmCheckoutFlowCartPageLoadedEventName(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_GTM_CHECKOUT_FLOW_CART_PAGE_LOADED_EVENT_NAME,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is GTM Checkout Flow Cart Page Loaded Enabled
     *
     * @return bool
     */
    public function isGtmCheckoutFlowCartPageLoadedEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_GTM_CHECKOUT_FLOW_CART_PAGE_LOADED_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get GTM Checkout Flow Cart Item Qty Changed Event Name
     *
     * @return string
     */
    public function getGtmCheckoutFlowCartItemQtyChangedEventName(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_GTM_CHECKOUT_FLOW_CART_ITEM_QTY_CHANGED_EVENT_NAME,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is GTM Checkout Flow Cart Item Qty Changed Enabled
     *
     * @return bool
     */
    public function isGtmCheckoutFlowCartItemQtyChangedEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_GTM_CHECKOUT_FLOW_CART_ITEM_QTY_CHANGED_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get GTM Checkout Flow Product Removed from Cart Event Name
     *
     * @return string
     */
    public function getGtmCheckoutFlowProductRemovedFromCartEventName(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_GTM_CHECKOUT_FLOW_PRODUCT_REMOVED_FROM_CART_EVENT_NAME,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is GTM Checkout Flow Product Removed from Cart Enabled
     *
     * @return bool
     */
    public function isGtmCheckoutFlowProductRemovedFromCartEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_GTM_CHECKOUT_FLOW_PRODUCT_REMOVED_FROM_CART_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get GTM Checkout Flow Product Added to Cart Event Name
     *
     * @return string
     */
    public function getGtmCheckoutFlowProductAddedToCartEventName(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_GTM_CHECKOUT_FLOW_PRODUCT_ADDED_TO_CART_EVENT_NAME,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is GTM Checkout Flow Product Added to Cart Enabled
     *
     * @return bool
     */
    public function isGtmCheckoutFlowProductAddedToCartEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_GTM_CHECKOUT_FLOW_PRODUCT_ADDED_TO_CART_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get GTM Swatch Click Event Name
     *
     * @return string
     */
    public function getGtmClickSwatchEventName(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_GTM_CLICK_SWATCH_EVENT_NAME,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is GTM Swatch Click enabled
     *
     * @return bool
     */
    public function isGtmClickSwatchEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_GTM_CLICK_SWATCH_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get GTM Menu Item Click Event Name
     *
     * @return string
     */
    public function getGtmClickMenuItemEventName(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_GTM_CLICK_MENU_ITEM_EVENT_NAME,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is GTM Menu Item Click enabled
     *
     * @return bool
     */
    public function isGtmClickMenuItemEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_GTM_CLICK_MENU_ITEM_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get GTM Product Click Event Name
     *
     * @return string
     */
    public function getGtmClickProductEventName(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_GTM_CLICK_PRODUCT_EVENT_NAME,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is GTM Product Click enabled
     *
     * @return bool
     */
    public function isGtmClickProductEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_GTM_CLICK_PRODUCT_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get GTM Page Load Handles List
     *
     * @return string
     */
    public function getGtmPageLoadHandlesList(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_GTM_PAGE_LOAD_HANDLES_LIST,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is GTM Page Load handles list used as the included pages, not excluded
     *
     * @return bool
     */
    public function isGtmPageLoadHandlesListInverted(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_GTM_PAGE_LOAD_HANDLES_LIST_BEHAVIOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get GTM Page Load Event Name
     *
     * @return string
     */
    public function getGtmPageLoadEventName(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_GTM_PAGE_LOAD_EVENT_NAME,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is GTM Page Load events are enabled
     *
     * @return bool
     */
    public function isGtmPageLoadEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_GTM_PAGE_LOAD_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is Google Tag Manager Debug enabled
     *
     * @return bool
     */
    public function isGtmDebugEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_GTM_DEBUG_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Phone Number
     *
     * @return string|null
     */
    public function getContainerId(): ?string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_GTM_CONTAINER_ID,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is Google Tag Manager enabled
     *
     * @return bool
     */
    public function isGtmEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_GTM_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }
}
