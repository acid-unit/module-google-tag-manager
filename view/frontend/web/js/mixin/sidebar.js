// noinspection JSUnusedGlobalSymbols,JSUnresolvedReference

/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

define([
    'jquery',
    'underscore',
    'jquery-ui-modules/widget'
], function (
    $,
    _
) {
    'use strict';

    return function (widget) {
        $.widget('mage.sidebar', widget, {

            /**
             * Update content after item remove
             * Mixin: passing full productData when triggering 'ajax:updateCartItemQty' event
             *
             * @param {Object} elem
             * @private
             */
            _removeItemAfter: function (elem) {
                const productData = this._getProductById(Number(elem.data('cart-item')));

                if (!_.isUndefined(productData)) {
                    $(document).trigger('ajax:removeFromCart', {
                        productIds: [productData['product_id']],
                        productInfo: [{'id': productData['product_id']}],
                        productData: productData
                    });

                    if (window.location.href.indexOf(this.shoppingCartUrl) === 0) {
                        window.location.reload();
                    }
                }
            },

            /**
             * Update content after update qty
             * Mixin: passing productData when triggering 'ajax:updateCartItemQty' event
             *
             * @param {HTMLElement} elem
             */
            _updateItemQtyAfter: function (elem) {
                const productData = this._getProductById(Number(elem.data('cart-item')));

                if (!_.isUndefined(productData)) {
                    $(document).trigger('ajax:updateCartItemQty', {
                        productData: productData
                    });

                    if (window.location.href === this.shoppingCartUrl) {
                        window.location.reload();
                    }
                }
                this._hideItemButton(elem);
            }
        });

        return $.mage.sidebar;
    };
});
