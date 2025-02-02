// noinspection JSUnresolvedReference

/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

define([
    './page-handle'
], function (
    handleModel
) {
    'use strict';

    return {
        productData: window.acidProductData ? window.acidProductData : {},
        productIdSelector: '.acid-product-id',

        /**
         * Get product data from 'window.acidProductData' object using product ID
         *
         * @param {string} productId
         * @return {object}
         */
        getProductDataById: function (productId) {
            const id = parseInt(productId, 10);

            if (id && this.productData && this.productData[id]) {
                return structuredClone(this.productData[id]);
            }

            return {};
        },

        /**
         * Get product ID from child '.acid-product-id' block
         * and pass forward to this.getProductDataById() method
         *
         * @param {HTMLElement} element
         * @returns {object}
         */
        getData: function (element) {
            const productIdElement = element.querySelector(this.productIdSelector);

            return productIdElement && productIdElement.dataset.id
                ? this.getProductDataById(productIdElement.dataset.id)
                : {};
        },

        /**
         * Get product list value based on the parent wrapper block class list
         *
         * @param {HTMLElement} element
         * @return {string}
         */
        getProductList: function (element) {
            const productsWrapper = element.closest('.products.wrapper');
            let list = '';

            if (productsWrapper.classList.value.indexOf('related') >= 0) {
                list = 'related';
            } else if (productsWrapper.classList.value.indexOf('upsell') >= 0) {
                list = 'upsell';
            } else if (productsWrapper.classList.value.indexOf('crosssell') >= 0) {
                list = 'crosssell';
            } else {
                list = handleModel.getCurrentPageName();
            }

            return list;
        }
    };
});
