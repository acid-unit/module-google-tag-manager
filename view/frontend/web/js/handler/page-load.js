// noinspection RedundantIfStatementJS, JSUnresolvedReference

/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

define([
    './../action/push',
    './../model/page-handle',
    './../model/page-data'
], function (
    push,
    handleModel,
    pageDataModel
) {
    'use strict';

    return {
        gtmConfig: window.acidGtmConfig ? window.acidGtmConfig : {},

        /**
         * @return {boolean}
         */
        isActive: function () {
            if (!this.gtmConfig['page_load'] || !this.gtmConfig['page_load']['enabled']) {
                return false;
            }

            const currentPageHandleCode = handleModel.getCurrentPageHandleCode(),
                handlesListInvertedBehavior = this.gtmConfig['page_load']['handles_list_inverted'],
                handlesListIncludesCurrentHandleCode =
                    this.gtmConfig['page_load']['handles_list'].split('\r\n').includes(currentPageHandleCode);

            // if current page handle is in the list with excluded option
            if (!handlesListInvertedBehavior && handlesListIncludesCurrentHandleCode) {
                return false;
            }

            // if current page handle is NOT in the list with included option
            if (handlesListInvertedBehavior && !handlesListIncludesCurrentHandleCode) {
                return false;
            }

            return true;
        },

        successOrder: function () {
            if (!this.isActive()) {
                return;
            }

            if (!this.gtmConfig['checkout_flow']['purchase_done']['enabled']) {
                return;
            }

            const pageData = pageDataModel.getPageData();

            push(this.gtmConfig['checkout_flow']['purchase_done']['event_name'], {
                'ecommerce': {
                    'purchase': {
                        'actionField': pageData['provider']['order_data'],
                        'products': pageData['provider']['products']
                    }
                }
            });
        },

        pdp: function () {
            if (!this.isActive()) {
                return;
            }

            const pageData = pageDataModel.getPageData();

            push(this.gtmConfig['page_load']['pdp_load_event_name'], {
                'ecommerce': {
                    'detail': {
                        'products': [pageData['provider']]
                    }
                }
            });
        },

        plp: function () {
            if (!this.isActive()) {
                return;
            }

            const pageData = pageDataModel.getPageData();

            push(this.gtmConfig['page_load']['plp_load_event_name'], {
                'ecommerce': {
                    'impressions': pageData['provider']
                }
            });
        },

        default: function () {
            if (!this.isActive()) {
                return;
            }

            const currentPageName = handleModel.getCurrentPageName(),
                eventName = this.gtmConfig['page_load']['event_name'];

            if (currentPageName) {
                const pageData = pageDataModel.getPageData(),
                    pushData = {
                        'page_type': currentPageName
                    };

                if (this.gtmConfig['page_load']['user_type_enabled']) {
                    pushData['user_type'] = pageData['user_type'];
                }

                push(eventName, pushData);
            } else if (document.querySelector('body').classList.value.split(' ').includes('account')) {
                /**
                 * Customer account can have different page handles,
                 * the solution to check body classes is simpler to write and support in this case
                 */
                push(eventName, {
                    'page_type': handleModel.handles.customerAccountPage.name
                });
            }
        }
    };
});
