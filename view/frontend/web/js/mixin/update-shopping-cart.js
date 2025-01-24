// noinspection JSUnusedGlobalSymbols

/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

define([
    'jquery',
    'jquery-ui-modules/widget'
], function (
    $
) {
    'use strict';

    return function (widget) {
        $.widget('mage.updateShoppingCart', widget, {
            /**
             * Validates updated shopping cart data.
             * Mixin: passing data to this.onSuccess() method when AJAX is done
             *
             * @param {String} url - request url
             * @param {Object} data - post data for ajax call
             */
            validateItems: function (url, data) {
                $.extend(data, {
                    'form_key': $.mage.cookies.get('form_key')
                });

                $.ajax({
                    url: url,
                    data: data,
                    type: 'post',
                    dataType: 'json',
                    context: this,

                    /** @inheritdoc */
                    beforeSend: function () {
                        $(document.body).trigger('processStart');
                    },

                    /** @inheritdoc */
                    complete: function () {
                        $(document.body).trigger('processStop');
                    }
                }).done(function (response) {
                    if (response.success) {
                        this.onSuccess(data);
                    } else {
                        this.onError(response);
                    }
                }).fail(function () {
                    this.submitForm();
                });
            },

            /**
             * Form validation succeed.
             * Mixin: passing serialized data when triggering 'ajax:updateCartItemQty' event
             *
             * @param {string} data
             */
            onSuccess: function (data) {
                $(document).trigger('ajax:' + this.options.eventName, {
                    productsSerializedData: data
                });

                this.submitForm();
            }
        });

        return $.mage.updateShoppingCart;
    };
});
