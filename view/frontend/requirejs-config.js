/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

// https://developer.adobe.com/commerce/frontend-core/javascript/requirejs/

let config = {
    'config': {
        'mixins': {
            'mage/menu': {
                'AcidUnit_GoogleTagManager/mage/menu-mixin': true
            },
            'Magento_Swatches/js/swatch-renderer': {
                'AcidUnit_GoogleTagManager/js/swatch-renderer-mixin': true
            }
        }
    }
};
