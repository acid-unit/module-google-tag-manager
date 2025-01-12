/*
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

define([
    './../action/push',
    './../model/page-handle'
], function (
    push,
    handleModel
) {
    'use strict';

    return {
        gtmConfig: window.acidGtmConfig ? window.acidGtmConfig : {},

        /**
         * @param {string} handle
         */
        init: function (handle) {
            handleModel.setCurrentPageHandleCode(handle.toLowerCase() || '');

            if (!this.gtmConfig['page_load'] || !this.gtmConfig['page_load']['enabled']) {
                return;
            }

            const currentPageName = handleModel.getCurrentPageName(),
                currentPageHandleCode = handleModel.getCurrentPageHandleCode(),
                eventName = this.gtmConfig['page_load']['event_name'];

            // if current page handle is in the list with excluded option
            if (!this.gtmConfig['page_load']['handles_list_inverted'] &&
                this.gtmConfig['page_load']['handles_list'].split('\r\n').includes(currentPageHandleCode)) {
                return;
            }

            // if current page handle is NOT in the list with included option
            if (this.gtmConfig['page_load']['handles_list_inverted'] &&
                !this.gtmConfig['page_load']['handles_list'].split('\r\n').includes(currentPageHandleCode)) {
                return;
            }

            if (currentPageName) {
                push(eventName, {
                    'pageType': currentPageName
                });
            } else if (document.querySelector('body').classList.value.split(' ').includes('account')) {
                /**
                 * Customer account can have different page handles,
                 * the solution to check body classes is simpler to write and support in this case
                 */
                push(eventName, {
                    'pageType': handleModel.handles.customerAccountPage.name
                });
            }
        }
    };
});
