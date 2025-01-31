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

        searchResultsPage: function () {
            if (!this.isActive()) {
                return;
            }

            const pageData = pageDataModel.getPageData();

            push(this.gtmConfig['page_load']['search_results_page_load_event_name'], {
                'ecommerce': {
                    'search_result': pageData['provider']
                },
                'search_query': new URLSearchParams(document.location.search).get('q')
            });
        },

        /**
         * @return {boolean}
         */
        customPage: function () {
            const customPages = JSON.parse(this.gtmConfig['page_load']['custom_pages'] || 'null');
            let isCustomPage = false;

            Object.keys(customPages).forEach(key => {
                if (customPages[key]['enabled'] !== '1') {
                    return;
                }

                if (window.location.href.includes(customPages[key]['url'])) {
                    isCustomPage = true;

                    push(customPages[key]['event'], {
                        'page_type': 'custom'
                    });
                }
            });

            return isCustomPage;
        },

        default: function () {
            if (!this.isActive()) {
                return;
            }

            if (this.customPage()) {
                return;
            }

            const currentPageName = handleModel.getCurrentPageName(),
                eventName = this.gtmConfig['page_load']['event_name'],
                h1 = document.querySelector('h1');

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
            } else if (h1) {
                /**
                 * If the page is not specified in handles list and is not customer account page,
                 * we just push <h1> inner text as a page type
                 */
                push(eventName, {
                    'page_type': h1.innerText
                });
            }
        }
    };
});
