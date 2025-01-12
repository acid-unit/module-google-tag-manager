/*
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

define([
    './model/page-data',
    './handler/page-events',
    './handler/page-load',
    './handler/click',
    './handler/exposure'
], function (
    pageData,
    pageEvents,
    pageLoad,
    click,
    exposure
) {
    'use strict';

    return function (config) {
        pageData.storePageData(config['pageData'] || {});

        pageEvents.registerEventListeners();
        pageEvents.triggerPageDataEvents();

        pageLoad.init(config['pageHandle'] || '');
        click.init();
        exposure.init();
    };
});
