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
         * @param {string} productId
         * @param {string} itemId
         * @return {object|false}
         */
        getProductData: function (productId, itemId) {
            let result = {};

            this.cartCustomerData().items.forEach(cartItem => {
                if (cartItem.product_id === productId &&
                    cartItem.item_id === itemId
                ) {
                    result.id = cartItem.product_id;
                    result.name = cartItem.product_name;
                    result.price = cartItem.product_price_value;
                    result.sku = cartItem.product_sku;
                    result.qty = cartItem.product_sku;
                    result['options'] = {};

                    cartItem.options.forEach(item => {
                        result['options'][item['code']] = item['value'];
                    });

                    if (!Object.keys(result['options']).length) {
                        delete result['options'];
                    }
                }
            });

            return JSON.parse(JSON.stringify(result));
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
                    'qty': item['qty']
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
