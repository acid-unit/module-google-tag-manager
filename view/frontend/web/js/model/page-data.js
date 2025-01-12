/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

define([], function () {
    'use strict';

    return {
        pageData: {},

        /**
         * @param {Object} data
         */
        storePageData: function (data) {
            if (!Object.keys(data).length) {
                return;
            }

            this.pageData = data;
        },

        /**
         * @returns {Object}
         */
        getPageData: function () {
            return this.pageData;
        }
    };
});
