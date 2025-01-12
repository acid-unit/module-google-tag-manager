/*
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/* eslint-disable max-nested-callbacks */

define([], function () {
    'use strict';

    return {
        /**
         * @param {object} newData
         */
        addSwatchData: function (newData) {
            const swatchData = window.acidSwatchData ? window.acidSwatchData : [];

            newData.forEach(newDataItem => {
                swatchData.forEach(swatchDataItem => {
                    if (swatchDataItem['id'] === newDataItem['id']) {
                        if (!swatchDataItem['options'] && newDataItem['options']) {
                            swatchDataItem['options'] = newDataItem['options'];
                            return;
                        }

                        if (swatchDataItem['options'] && !newDataItem['options']) {
                            return;
                        }

                        newDataItem['options'].forEach(newDataOption => {
                            let match = false;

                            swatchDataItem['options'].forEach(swatchDataOption => {
                                if (swatchDataOption['id'] === newDataOption['id']) {
                                    swatchDataOption['products'] =
                                        swatchDataOption['products'].concat(newDataOption['products']);
                                    match = true;
                                }
                            });

                            if (!match) {
                                swatchDataItem['options'].push(newDataOption);
                            }
                        });
                    }
                });
            });

            window.acidSwatchData = !Object.keys(swatchData).length ? newData : swatchData;
        }
    };
});
