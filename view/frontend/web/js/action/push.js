/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

define([], function () {
    'use strict';

    // noinspection JSUnresolvedReference
    const gtmConfig = window.acidGtmConfig ? window.acidGtmConfig : {};

    function push(data, object) {
        if (object) {
            Object.assign(data, object);
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
