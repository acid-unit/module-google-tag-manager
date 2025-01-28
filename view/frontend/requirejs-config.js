/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

// https://developer.adobe.com/commerce/frontend-core/javascript/requirejs/

let config = {
    'config': {
        'mixins': {
            'mage/menu': {
                'AcidUnit_GoogleTagManager/js/mixin/menu': true
            },
            'Magento_Checkout/js/sidebar': {
                'AcidUnit_GoogleTagManager/js/mixin/sidebar': true
            },
            'Magento_Checkout/js/model/step-navigator': {
                'AcidUnit_GoogleTagManager/js/mixin/step-navigator': true
            },
            'Magento_Swatches/js/swatch-renderer': {
                'AcidUnit_GoogleTagManager/js/mixin/swatch-renderer': true
            },
            'Magento_Checkout/js/action/update-shopping-cart': {
                'AcidUnit_GoogleTagManager/js/mixin/update-shopping-cart': true
            }
        }
    }
};
