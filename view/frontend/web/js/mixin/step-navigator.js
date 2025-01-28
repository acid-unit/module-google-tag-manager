/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/* eslint-disable indent */

define([
    'mage/utils/wrapper',
    './../handler/checkout-flow'
], function (
    wrapper,
    checkoutFlow
) {
    'use strict';

    return function (stepNavigator) {
        stepNavigator.handleHash = wrapper.wrapSuper(stepNavigator.handleHash, function () {
            this._super();

            const hashString = window.location.hash.replace('#', '');

            switch (hashString) {
                case checkoutFlow.checkoutStepsModel.shipping.code:
                    // do nothing. event is sent when the module is initialized
                    break;
                case checkoutFlow.checkoutStepsModel.payment.code:
                    // send GTM event when checkout payment step is loaded
                    checkoutFlow.checkoutStepLoaded(checkoutFlow.checkoutStepsModel.payment.number);
            }
        });

        return stepNavigator;
    };
});
