// noinspection DuplicatedCode,JSUnresolvedReference

/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

define([
    'jquery',
    './../action/push',
    './../model/product-data',
    './../model/page-handle',
    './../vendor/jquery-scrollspy/jquery-scrollspy'
], function (
    $,
    push,
    productDataModel,
    handleModel
) {
    'use strict';

    return {
        gtmConfig: window.acidGtmConfig ? window.acidGtmConfig : {},
        generalConfig: window.acidGeneralConfig ? window.acidGeneralConfig : {},

        productExposures: [],
        blockExposures: [],
        productCombineExposureTimeout: null,
        blockCombineExposureTimeout: null,
        exposureCombineTimer: 100,

        model: {
            productSelectors: [
                '.product-items .product-item'
            ]
        },

        /**
         * Check if element is visible on the screen (even particularly)
         *
         * @param {HTMLElement} element
         * @returns {boolean}
         */
        isElementInViewport: function (element) {
            const rect = element.getBoundingClientRect(),
                viewHeight = Math.max(document.documentElement.clientHeight, window.innerHeight),
                viewWidth = Math.max(document.documentElement.clientWidth, window.innerWidth);

            return !(
                rect.bottom <= 0 ||
                rect.top - viewHeight >= 0 ||
                rect.left + rect.width <= 0 ||
                rect.right - rect.width >= viewWidth
            );
        },

        /**
         * @param {array} products
         * @returns {array}
         */
        getProductsData: function (products) {
            const resultArray = [];
            let productData,
                pushData;

            products.forEach(product => {
                productData = productDataModel.getData(product);

                if (!Object.keys(productData).length) {
                    return;
                }

                pushData = {...productData};

                const productList = productDataModel.getProductList(product);

                if (productList) {
                    pushData['list'] = productList;
                }

                resultArray.push(pushData);
            });

            return resultArray;
        },

        /**
         * @param {array} products
         */
        pushProductExposures: function (products) {
            const productData = this.getProductsData(products);

            if (!productData.length) {
                return;
            }

            push(this.gtmConfig['exposure']['product']['event_name'], {
                'page': handleModel.getCurrentPageName(),
                'ecommerce': {
                    'exposure': productData,
                    'currencyCode': this.generalConfig['currency']['code']
                }
            });
        },

        exposeProductsOnScroll: function () {
            $(this.model.productSelectors.join(', ')).scrollspy({
                onView: element => {
                    clearTimeout(this.productCombineExposureTimeout);

                    if (this.isElementInViewport(element) && element.dataset.exposed !== '1') {
                        element.dataset.exposed = '1';
                        this.productExposures.push(element);
                    }

                    this.productCombineExposureTimeout = setTimeout(() => {
                        if (this.productExposures.length) {
                            this.pushProductExposures(this.productExposures);
                        }

                        this.productExposures = [];
                    }, this.exposureCombineTimer);
                }
            });
        },

        exposeProductsOnInit: function () {
            this.model.productSelectors.forEach(item => {
                const elements = document.querySelectorAll(item);

                elements.forEach(element => {
                    if (this.isElementInViewport(element) && element.dataset.exposed !== '1') {
                        element.dataset.exposed = '1';
                        this.productExposures.push(element);
                    }
                });

                if (this.productExposures.length) {
                    this.pushProductExposures(this.productExposures);
                }

                this.productExposures = [];
            });
        },

        /**
         * @param {array} blocks
         * @returns {array}
         */
        getBlocksData: function (blocks) {
            const resultArray = [];

            blocks.forEach(block => {
                resultArray.push({
                    'name': block.dataset.name,
                    'page': handleModel.getCurrentPageName()
                });
            });

            return resultArray;
        },

        /**
         * @param {array} blocks
         */
        pushBlockExposures: function (blocks) {
            const blocksData = this.getBlocksData(blocks);

            if (!blocksData.length) {
                return;
            }

            push(this.gtmConfig['exposure']['block']['event_name'], {
                'ecommerce': {
                    'blockView': blocksData,
                    'currencyCode': this.generalConfig['currency']['code']
                }
            });
        },

        exposeBlocksOnScroll: function () {
            const blocksJson = JSON.parse(this.gtmConfig['exposure']['block']['config']);

            Object.keys(blocksJson).forEach(key => {
                if (blocksJson[key]['enabled'] !== '1') {
                    return;
                }

                $(blocksJson[key]['selector']).scrollspy({
                    onView: element => {
                        clearTimeout(this.blockCombineExposureTimeout);

                        if (this.isElementInViewport(element) && element.dataset.exposed !== '1') {
                            element.dataset.exposed = '1';
                            this.blockExposures.push(element);
                        }

                        this.blockCombineExposureTimeout = setTimeout(() => {
                            if (this.blockExposures.length) {
                                this.pushBlockExposures(this.blockExposures);
                            }

                            this.blockExposures = [];
                        }, this.exposureCombineTimer);
                    }
                });
            });
        },

        exposeBlocksOnInit: function () {
            const blocksJson = JSON.parse(this.gtmConfig['exposure']['block']['config']);

            Object.keys(blocksJson).forEach(key => {
                if (blocksJson[key]['enabled'] !== '1') {
                    return;
                }

                const elements = document.querySelectorAll(blocksJson[key]['selector']);

                elements.forEach(element => {
                    element.dataset.name = blocksJson[key]['name'];

                    if (this.isElementInViewport(element) && element.dataset.exposed !== '1') {
                        element.dataset.exposed = '1';
                        this.blockExposures.push(element);
                    }
                });

                if (this.blockExposures.length) {
                    this.pushBlockExposures(this.blockExposures);
                }

                this.blockExposures = [];
            });
        },

        init: function () {
            if (this.gtmConfig['exposure']['product']['enabled']) {
                this.exposeProductsOnInit();
                this.exposeProductsOnScroll();
            }

            if (this.gtmConfig['exposure']['block']['config']) {
                this.exposeBlocksOnInit();
                this.exposeBlocksOnScroll();
            }
        }
    };
});
