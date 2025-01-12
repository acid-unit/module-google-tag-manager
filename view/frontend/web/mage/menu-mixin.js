/*
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

define([
    'jquery',
    './../js/action/push',
    './../js/model/menu-data',
    'jquery-ui-modules/widget'
], function (
    $,
    push,
    menuDataModel
) {
    'use strict';

    return function (widget) {
        $.widget('mage.menu', widget.menu, {
            gtmConfig: window.acidGtmConfig ? window.acidGtmConfig : {},

            /**
             * @private
             */
            _toggleDesktopMode: function () {
                this._super();

                if (!this.gtmConfig['exposure']['menu_category']['enabled']) {
                    return;
                }

                const eventName = this.gtmConfig['exposure']['menu_category']['event_name'];

                this._on({
                    'mouseenter .ui-menu-item': event => {
                        if (event.target.closest('.category-item').classList.contains('parent')) {
                            const target = event.target,
                                menuItemHref = target.getAttribute('href'),
                                menuItemData = menuDataModel.getMenuItemData(menuItemHref);

                            push(eventName, {
                                'ecommerce': {
                                    'menuHover': menuItemData
                                }
                            });
                        }
                    }
                });
            }
        });

        return {
            menu: $.mage.menu,
            navigation: $.mage.navigation
        };
    };
});
