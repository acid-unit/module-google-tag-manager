/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

define([
    'Magento_Customer/js/customer-data'
], function (
    customerData
) {
    'use strict';

    // noinspection JSUnresolvedReference
    const gtmConfig = window.acidGtmConfig ? window.acidGtmConfig : {},
        loggedAsCustomerData = customerData.get('loggedAsCustomer');

    function push(data, object) {
        if (object) {
            Object.assign(data, object);
        }

        if (gtmConfig['prevent_push_when_logged_as_customer_enabled'] &&
            loggedAsCustomerData().hasOwnProperty('adminUserId')
        ) {
            return;
        }

        if (window.hasOwnProperty('dataLayer')) {
            window['dataLayer'].push(data);

            if (gtmConfig['debug_enabled']) {
                console.log(data);
            }
        }
    }

    return function (event, object) {
        const data = !event ? {} : {'event': event};

        push(data, object);
    };
});
