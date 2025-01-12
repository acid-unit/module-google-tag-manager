/*
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

define([
    'jquery',
    './action/push',
    './handler/click',
    './model/product-data',
    './model/swatch-data',
    'jquery-ui-modules/widget'
], function (
    $,
    push,
    clickHandler,
    productDataModel,
    swatchDataModel
) {
    'use strict';

    return function (widget) {
        $.widget('mage.SwatchRenderer', widget, {
            gtmConfig: window.acidGtmConfig ? window.acidGtmConfig : {},
            productData: window.acidProductData ? window.acidProductData : {},

            _init: function () {
                this._super();

                const swatchData = this.options['jsonConfig']
                    ? this.options['jsonConfig'].attributes
                    : {};

                swatchDataModel.addSwatchData(swatchData);
            },

            /**
             * @param {object} $this
             * @param {object} $widget
             * @private
             */
            _OnClick: function ($this, $widget) {
                this._super($this, $widget);

                if (!this.gtmConfig['click']['swatch']['enabled']) {
                    return;
                }

                const swatchData = window.acidSwatchData ? window.acidSwatchData : {},
                    swatchCode = $this.closest('.swatch-attribute').data('attribute-code'),
                    optionId = $this.data('option-id'),
                    swatchItem = swatchData.filter(item => item['code'] === swatchCode)[0],
                    optionItem = swatchItem['options'].filter(option => option['id'] === optionId.toString())[0],
                    eventName = this.gtmConfig['click']['swatch']['event_name'];

                let productId,
                    productItem,
                    productData;

                if ($widget.inProductList) {
                    productItem = $this.closest(clickHandler.model.product.productItemSelector);
                    productData = productDataModel.getData(productItem[0]);
                } else {
                    productId = $this.closest('#product_addtocart_form').find('input[name="product"]').val();
                    productData = this.productData[productId];
                }

                push(eventName, {
                    'swatchLabel': swatchItem.label,
                    'optionLabel': optionItem.label,
                    'inProductList': $widget.inProductList,
                    'product': productData
                });
            }
        });

        return $.mage.SwatchRenderer;
    };
});
