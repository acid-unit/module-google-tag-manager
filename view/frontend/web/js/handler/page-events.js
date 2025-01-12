/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

define([
    'jquery',
    './../model/page-data',
    './../action/push'
], function (
    $,
    pageDataModel,
    push
) {
    'use strict';

    // noinspection JSUnusedGlobalSymbols
    return {
        pageDataEvents: ['gtm_event'],
        gtmConfig: window.acidGtmConfig ? window.acidGtmConfig : {},

        /**
         * @param {Object} event
         * @param {Object} data
         */
        loginSuccessful: function (event, data) {
            push(this.gtmConfig['customer_session']['login']['event_name'], data);
        },

        /**
         * @param {Object} event
         * @param {Object} data
         */
        loginFailed: function (event, data) {
            push(this.gtmConfig['customer_session']['login']['failed_event_name'], data);
        },

        logoutSuccessful: function () {
            push(this.gtmConfig['customer_session']['logout']['event_name']);
        },

        /**
         * @param {Object} event
         * @param {Object} data
         */
        registrationSuccessful: function (event, data) {
            push(this.gtmConfig['customer_session']['registration']['event_name'], data);
        },

        /**
         * @param {Object} event
         * @param {Object} data
         */
        registrationFailed: function (event, data) {
            push(this.gtmConfig['customer_session']['registration']['failed_event_name'], data);
        },

        /**
         * Register event listeners from GTM config 'page_events' property.
         * If the value for key is not set, we should skip registration.
         *
         * Event callback will be registered to the method that is named exactly as the property value
         */
        registerEventListeners: function () {
            if (!Object.values(this.gtmConfig['page_events']).length) {
                return;
            }

            Object.values(this.gtmConfig['page_events']).forEach(event => {
                if (!event) {
                    return;
                }

                $('.acid-gtm').on(event, this[event].bind(this));
            });
        },

        /**
         * Trigger events based on the page data
         */
        triggerPageDataEvents: function () {
            const pageData = pageDataModel.getPageData();

            this.pageDataEvents.forEach(item => {
                if (pageData.hasOwnProperty(item)) {
                    const event = pageData[item]['event'],
                        data = pageData[item]['data'] ? pageData[item]['data'] : {};

                    $('.acid-gtm').trigger(event, data);
                }
            });
        }
    };
});
