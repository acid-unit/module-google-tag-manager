/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/* eslint-disable indent */

define([
    './model/page-data',
    './model/page-handle',
    './handler/page-data-events',
    './handler/page-load',
    './handler/click',
    './handler/exposure',
    './handler/checkout-flow'
], function (
    pageDataModel,
    handleModel,
    pageDataEvents,
    pageLoad,
    click,
    exposure,
    checkoutFlow
) {
    'use strict';

    return function (config) {
        pageDataModel.storePageData(config['pageData'] || {});
        pageDataEvents.trigger();
        handleModel.setCurrentPageHandleCode(config['pageHandle'] || '');

        switch (handleModel.getCurrentPageHandleCode()) {
            case handleModel.handles.productPage.code:
                pageLoad.pdp();
                break;
            case handleModel.handles.categoryPage.code:
                pageLoad.plp();
                break;
            case handleModel.handles.successOrderPage.code:
                pageLoad.successOrder();
                break;
            case handleModel.handles.checkoutPage.code:
                checkoutFlow.checkoutStepLoaded(1);
                break;
            default:
                pageLoad.default();
        }

        click.init();
        exposure.init();
        checkoutFlow.init();
    };
});
