// noinspection JSUnusedGlobalSymbols,JSUnresolvedReference

/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

define([
    'jquery',
    './../model/page-data',
    './../handler/checkout-flow',
    './../action/push'
], function (
    $,
    pageDataModel,
    checkoutFlow,
    push
) {
    'use strict';

    return {
        gtmConfig: window.acidGtmConfig ? window.acidGtmConfig : {},

        /**
         * @param {Object} data
         */
        loginSuccessful: function (data) {
            push(this.gtmConfig['customer_session']['login']['event_name'], data);
        },

        /**
         * @param {Object} data
         */
        loginFailed: function (data) {
            push(this.gtmConfig['customer_session']['login']['failed_event_name'], data);
        },

        logoutSuccessful: function () {
            push(this.gtmConfig['customer_session']['logout']['event_name']);
        },

        /**
         * @param {Object} data
         */
        registrationSuccessful: function (data) {
            push(this.gtmConfig['customer_session']['registration']['event_name'], data);
        },

        /**
         * @param {Object} data
         */
        registrationFailed: function (data) {
            push(this.gtmConfig['customer_session']['registration']['failed_event_name'], data);
        },

        /**
         * @param {Object} data
         */
        productRemovedFromCart: function (data) {
            checkoutFlow.processRemoveFromCart({}, data, false);
        },

        /**
         * @param {Object} data
         */
        productAddedToWishlist: function (data) {
            push(this.gtmConfig['wishlist']['add']['event_name'], data);
        },

        /**
         * @param {Object} data
         */
        productRemovedFromWishlist: function (data) {
            push(this.gtmConfig['wishlist']['remove']['event_name'], data);
        },

        /**
         * Trigger disposable events if exist
         */
        triggerEvents: function () {
            this.disposableEvents = Object.values(this.gtmConfig['disposable_events']);
            this.pageData = pageDataModel.getPageData();

            if (this.pageData.hasOwnProperty('disposable')) {
                const eventName = this.pageData['disposable']['event'],
                    eventData = this.pageData['disposable']['data']
                        ? this.pageData['disposable']['data']
                        : {};

                if (!this.disposableEvents.length || !this.disposableEvents.includes(eventName)) {
                    return;
                }

                this[eventName](eventData);
            }
        }
    };
});
