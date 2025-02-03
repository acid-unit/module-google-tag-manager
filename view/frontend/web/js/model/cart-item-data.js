// noinspection JSUnresolvedReference

/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/* eslint-disable max-nested-callbacks */

define([
    'Magento_Customer/js/customer-data'
], function (
    customerData
) {
    'use strict';

    return {
        cartCustomerData: customerData.get('cart'),
        oldQtyData: [],

        /**
         * @return {array}
         */
        getOldQtyData: function () {
            return this.oldQtyData;
        },

        /**
         * Get product data from customer data 'cart' section
         *
         * @param {string} itemId
         * @return {object}
         */
        getProductData: function (itemId) {
            let result = {};

            if (!this.cartCustomerData().items) {
                return result;
            }

            this.cartCustomerData().items.forEach(cartItem => {
                if (cartItem.item_id === itemId) {
                    result.id = cartItem.product_id;
                    result.name = cartItem.product_name;
                    result.price = cartItem.product_price_value;
                    result.sku = cartItem.product_sku;
                    result.qty = cartItem.qty;

                    if (cartItem.options && cartItem.options.length) {
                        result['options'] = {};

                        cartItem.options.forEach(item => {
                            result['options'][item['code']] = item['value'];
                        });
                    }
                }
            });

            return structuredClone(result);
        },

        /**
         * Get product options from customer data 'cart' section
         *
         * @param {string} itemId
         * @return {object}
         */
        getProductOptions: function (itemId) {
            let result = {};

            this.cartCustomerData().items.forEach(cartItem => {
                if (cartItem.item_id === itemId && cartItem.options && cartItem.options.length) {
                    cartItem.options.forEach(item => {
                        result[item['code']] = item['value'];
                    });
                }
            });

            return structuredClone(result);
        },

        /**
         * Store products qty data from customer data 'cart' section
         *
         * @param {Object} cartData
         */
        refreshOldQtyData: function (cartData = {}) {
            const cartItems = cartData.items ? cartData.items : this.cartCustomerData().items;

            if (!cartItems || !cartItems.length) {
                return;
            }

            this.oldQtyData = [];

            cartItems.forEach(item => {
                this.oldQtyData.push({
                    'item_id': item['item_id'],
                    'qty': parseInt(item['qty'], 10)
                });
            });
        },

        /**
         * Initialize old qty storage
         */
        initOldQtyData: function () {
            this.cartCustomerData.subscribe(value => {
                this.refreshOldQtyData(value);
            });

            this.refreshOldQtyData();
        }
    };
});
