/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

define([], function () {
    'use strict';

    return {
        topMenuData: window['acidTopMenuData'] ? window['acidTopMenuData'] : {},

        menuItem: {
            selectors: [
                'a.ui-menu-item-wrapper'
            ],
            foundFlag: false,
            pathDivider: ' > '
        },

        /**
         * @param {object} topMenuItem
         * @param {string} clickedMenuItemHref
         * @param {object} result
         * @return {object}
         */
        parseMenuChildrenItemsForData: function (topMenuItem, clickedMenuItemHref, result) {
            const menuItemKeys = Object.keys(topMenuItem);

            for (let i = 0; i < menuItemKeys.length; i++) {
                const menuItem = topMenuItem[menuItemKeys[i]];

                if (menuItem['children']) {
                    result = this.parseMenuChildrenItemsForData(menuItem['children'], clickedMenuItemHref, result);
                }

                if (this.menuItem.foundFlag) {
                    result['path'] = this.menuItem.pathDivider + menuItem['name'] + result['path'];
                    break;
                }

                if (menuItem['url'] === clickedMenuItemHref) {
                    result['name'] = menuItem['name'];
                    result['path'] = result['path'] + this.menuItem.pathDivider + menuItem['name'];
                    this.menuItem.foundFlag = true;
                    break;
                }
            }

            return result;
        },

        /**
         * @param {string} clickedMenuItemHref
         * @return {object}
         */
        getMenuItemData: function (clickedMenuItemHref) {
            let result = {
                name: '',
                path: ''
            };

            if (!clickedMenuItemHref) {
                return result;
            }

            result = this.parseMenuChildrenItemsForData(this.topMenuData, clickedMenuItemHref, result);
            this.menuItem.foundFlag = false;

            if (result['path'].startsWith(this.menuItem.pathDivider)) {
                result['path'] = result['path'].substring(this.menuItem.pathDivider.length);
            }

            return result;
        }
    };
});
