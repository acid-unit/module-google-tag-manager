/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

define([], function () {
    'use strict';

    return {
        handles: {
            productPage: {
                code: 'catalog_product_view',
                name: 'Product Page'
            },
            categoryPage: {
                code: 'catalog_category_view',
                name: 'Category Page'
            },
            searchResultsPage: {
                code: 'catalogsearch_result_index',
                name: 'Search Results'
            },
            customerAccountIndexPage: {
                code: 'customer_account_index',
                name: 'Customer Account Index Page'
            },
            customerAccountPage: {
                name: 'Customer Account Page'
            },
            cartPage: {
                code: 'checkout_cart_index',
                name: 'Cart Page'
            },
            checkoutPage: {
                code: 'checkout_index_index',
                name: 'Checkout'
            },
            homePage: {
                code: 'cms_index_index',
                name: 'Home Page'
            },
            cmsPage: {
                code: 'cms_page_view',
                name: 'CMS Page'
            },
            contactPage: {
                code: 'contact_index_index',
                name: 'Contact Us Page'
            },
            loginPage: {
                code: 'customer_account_login',
                name: 'Customer Login Page'
            },
            registrationPage: {
                code: 'customer-account-create',
                name: 'Customer Registration Page'
            }
        },

        currentPageHandleCode: '',

        /**
         * @param {string} code
         */
        setCurrentPageHandleCode: function (code) {
            if (!code) {
                return;
            }

            this.currentPageHandleCode = code;
        },

        /**
         * @returns {string}
         */
        getCurrentPageHandleCode: function () {
            return this.currentPageHandleCode;
        },

        /**
         * @returns {string}
         */
        getCurrentPageName: function () {
            let result = '';

            Object.keys(this.handles).forEach(key => {
                if (this.handles[key].hasOwnProperty('code') &&
                    this.handles[key].hasOwnProperty('name') &&
                    this.handles[key]['code'] === this.getCurrentPageHandleCode()
                ) {
                    result = this.handles[key]['name'];
                }
            });

            return result;
        }
    };
});
