/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

define([], function () {
    'use strict';

    return {
        productData: window.acidProductData ? window.acidProductData : {},
        productIdSelector: '.acid-product-id',

        /**
         * @param {string} productId
         * @return {object}
         */
        getProductDataById: function (productId) {
            const id = parseInt(productId, 10);

            if (id && this.productData && this.productData[id]) {
                return this.productData[id];
            }

            return {};
        },

        /**
         * @param {HTMLElement} element
         * @returns {object}
         */
        getData: function (element) {
            const productIdElement = element.querySelector(this.productIdSelector);

            return productIdElement && productIdElement.dataset.id
                ? this.getProductDataById(productIdElement.dataset.id)
                : {};
        }
    };
});
