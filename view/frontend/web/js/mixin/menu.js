// noinspection JSUnresolvedReference

/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

define([
    'jquery',
    './../action/push',
    './../model/menu-data',
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
             * Mixin: everything after this._super();
             *
             * @private
             */
            _toggleDesktopMode: function () {
                this._super();

                if (!this.gtmConfig['exposure']['menu_category']['enabled']) {
                    return;
                }

                // push GTM event on menu item hover
                this._on({
                    'mouseenter .ui-menu-item': event => {
                        if (event.target.closest('.category-item').classList.contains('parent')) {
                            let target = event.target;

                            if (target.tagName !== 'a') {
                                target = target.closest('a');
                            }

                            if (!target.classList.value.includes('ui-menu-item-wrapper')) {
                                return;
                            }

                            const menuItemHref = target.getAttribute('href'),
                                menuItemData = menuDataModel.getMenuItemData(menuItemHref);

                            push(this.gtmConfig['exposure']['menu_category']['event_name'], {
                                'ecommerce': {
                                    'menu_hover': menuItemData
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
