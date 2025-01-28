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

    // noinspection JSUnusedGlobalSymbols
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
         * @param data
         */
        productRemovedFromCart: function (data) {
            checkoutFlow.processRemoveFromCart({}, data, false);
        },

        /**
         * Trigger disposable events if exist
         */
        trigger: function () {
            this.activePageLoadEvents = Object.values(this.gtmConfig['page_load_events']);
            this.pageData = pageDataModel.getPageData();

            if (this.pageData.hasOwnProperty('disposable')) {
                const eventName = this.pageData['disposable']['event'],
                    eventData = this.pageData['disposable']['data']
                        ? this.pageData['disposable']['data']
                        : {};

                if (!this.activePageLoadEvents.length || !this.activePageLoadEvents.includes(eventName)) {
                    return;
                }

                this[eventName](eventData);
            }
        }
    };
});
