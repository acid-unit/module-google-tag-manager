/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

define([
    'jquery',
    'ko',
    './../action/push',
    './../model/cart-item-data',
    './../model/product-data',
    'Magento_Customer/js/customer-data'
], function (
    $,
    ko,
    push,
    cartItemDataModel,
    productDataModel,
    customerData
) {
    'use strict';

    return {
        gtmConfig: window.acidGtmConfig ? window.acidGtmConfig : {},
        productData: window.acidProductData ? window.acidProductData : {},
        cartCustomerData: customerData.get('cart'),

        /**
         * window.acidSwatchData is populated dynamically via JS, we can't use that object on initialization
         * because we can't be sure it contains all the data at that moment
         */
        swatchData: [],

        checkoutStepsModel: {
            shipping: {
                code: 'shipping',
                number: 1
            },
            payment: {
                code: 'payment',
                number: 2
            }
        },

        quoteItems: window.checkoutConfig && window.checkoutConfig.hasOwnProperty('quoteItemData')
            ? window.checkoutConfig['quoteItemData']
            : [],

        /**
         * @param {number} stepNumber
         */
        checkoutStepLoaded: function (stepNumber) {
            if (!this.gtmConfig['checkout_flow']['checkout_steps_reached']['enabled']) {
                return;
            }

            const data = {
                    'ecommerce': {
                        'checkout': {
                            'actionField': {
                                'step': stepNumber
                            }
                        }
                    }
                },
                eventName = this.gtmConfig['checkout_flow']['checkout_steps_reached']['event_name'],
                products = [];

            if (stepNumber === this.checkoutStepsModel.shipping.number) {
                this.quoteItems.forEach(item => {
                    const productId = item['product_id'],
                        productData = productDataModel.getProductDataById(productId),
                        pushData = {
                            'id': item.item_id,
                            'name': item.name,
                            'price': parseFloat(item.price),
                            'qty': item.qty
                        };

                    pushData.options = cartItemDataModel.getProductOptions(item.item_id);

                    if (!Object.keys(pushData.options).length) {
                        delete pushData['options'];
                    }

                    Object.assign(pushData, productData);
                    products.push(pushData);
                });

                if (!products.length) {
                    return;
                }

                data.ecommerce['products'] = products;
            }

            push(eventName, data);
        },

        /**
         * Get swatch data using the 'window.acidSwatchData' array
         *
         * @param {Object} data
         * @param {string} swatchCode
         * @param {string} productId
         * @returns {string}
         */
        getSwatchData: function (data, swatchCode, productId) {
            const swatchItem = this.swatchData.filter(item => item['code'] === swatchCode)[0],
                swatchElement = data.form[0].querySelector(
                    '.swatch-attribute[data-attribute-code="' + swatchCode + '"]'
                );
            let optionId,
                optionItem;

            if (!swatchElement) {
                // if PLP
                data.productInfo.filter(product => product.id === productId)[0].optionValues.forEach(optionValue => {
                    swatchItem['options'].forEach(swatchOption => {
                        if (optionValue === swatchOption['id']) {
                            optionId = optionValue;
                        }
                    });
                });
            } else {
                // if PDP
                optionId = swatchElement.dataset.optionSelected;
            }

            optionItem = swatchItem['options'].filter(option => option['id'] === optionId.toString())[0];

            return optionItem ? optionItem.label : '';
        },

        /**
         * @param {Object} data
         * @returns {string}
         */
        getQtyFromDom: function (data) {
            const qtyElement = data.form.find('#qty');

            return qtyElement.length ? qtyElement.val() : '1';
        },

        /**
         * @param {Object} event
         * @param {Object} data
         */
        processAddToCart: async function (event, data) {
            let productId = '';

            Object.entries(this.productData).forEach(product => {
                if (product[1].sku === data.sku) {
                    productId = product[0];
                }
            });

            const productData = productDataModel.getProductDataById(productId),
                eventName = this.gtmConfig['checkout_flow']['product_added_to_cart']['event_name'];

            if (!productData || !data.form) {
                return;
            }

            productData['qty'] = this.getQtyFromDom(data);

            if (this.productData[productId].type === 'configurable') {
                if (!this.swatchData.length) {
                    this.swatchData = window.acidSwatchData ? window.acidSwatchData : [];
                }

                if (this.swatchData.length) {
                    productData['options'] = {};

                    this.swatchData.forEach(item => {
                        productData['options'][item['code']] = this.getSwatchData(data, item['code'], productId);
                    });
                }
            }

            push(eventName, {
                'ecommerce': {
                    'add': {
                        'products': [productData]
                    }
                }
            });
        },

        /**
         * @param {Object} event
         * @param {Object} data
         * @param {boolean} getDataFromCartModel
         */
        processRemoveFromCart: function (event, data, getDataFromCartModel = true) {
            const eventName = this.gtmConfig['checkout_flow']['product_removed_from_cart']['event_name'];
            let productData;

            if (getDataFromCartModel) {
                const itemId = data['productData']['item_id'];

                productData = cartItemDataModel.getProductData(itemId);
                productData.qty = data.productData.qty.toString();
            } else {
                productData = data;
            }

            push(eventName, {
                'ecommerce': {
                    'remove': {
                        'products': [productData]
                    }
                }
            });
        },

        /**
         * @param {string} serializedData
         * @return {object}
         */
        unserialize: function (serializedData) {
            const urlParams = new URLSearchParams(serializedData),
                unserializedData = {};

            for (const [key, value] of urlParams) {
                unserializedData[key] = value;
            }

            delete unserializedData['form_key'];
            return unserializedData;
        },

        /**
         * @param {Object} event
         * @param {Object} data
         */
        processCartItemQtyChanged: function (event, data) {
            const products = [],
                eventName = this.gtmConfig['checkout_flow']['cart_item_qty_changed']['event_name'];
            let productData;

            if (data.productData) {
                // updating qty from minicart
                const itemId = data.productData.item_id,
                    cartItem = cartItemDataModel.getOldQtyData().filter(item => item.item_id === itemId)[0];

                productData = cartItemDataModel.getProductData(itemId);
                productData.qty = data.productData.qty.toString();
                productData.old_qty = cartItem.qty.toString();

                products.push(productData);
            } else if (data.productsSerializedData) {
                // updating qty on cart page
                const unserialized = this.unserialize(data.productsSerializedData);

                Object.keys(unserialized).forEach(key => {
                    const itemId = key.replace('cart[', '').replace('][qty]', ''),
                        cartItem = this.cartCustomerData().items.filter(item => item.item_id === itemId)[0],
                        oldQty = cartItem.qty.toString(),
                        newQty = unserialized[key];

                    if (oldQty !== newQty) {
                        productData = cartItemDataModel.getProductData(cartItem.item_id);
                        productData.qty = newQty;
                        productData.old_qty = oldQty;

                        products.push(productData);
                    }
                });
            }

            push(eventName, {
                'ecommerce': {
                    'update': {
                        'products': products
                    }
                }
            });
        },

        bindAddToCartEvent: function () {
            $(document).on('ajax:addToCart', this.processAddToCart.bind(this));
        },

        bindRemoveFromCartEvent: function () {
            $(document).on('ajax:removeFromCart', this.processRemoveFromCart.bind(this));
        },

        bindCartItemQtyChangedEvent: function () {
            $(document).on('ajax:updateCartItemQty', this.processCartItemQtyChanged.bind(this));
        },

        init: function () {
            if (this.gtmConfig['checkout_flow']['product_added_to_cart']['enabled']) {
                this.bindAddToCartEvent();
            }

            if (this.gtmConfig['checkout_flow']['product_removed_from_cart']['enabled']) {
                this.bindRemoveFromCartEvent();
            }

            if (this.gtmConfig['checkout_flow']['cart_item_qty_changed']['enabled']) {
                cartItemDataModel.initOldQtyData();
                this.bindCartItemQtyChangedEvent();
            }
        }
    };
});
