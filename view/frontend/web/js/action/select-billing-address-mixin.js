define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper, quote) {
    'use strict';
    return function (setBillingAddressAction) {
        return wrapper.wrap(setBillingAddressAction, function (originalAction, billingAddress) {
            if (billingAddress['extension_attributes'] === undefined) {
                billingAddress['extension_attributes'] = {};
            }

            billingAddress['extension_attributes']['city_id'] = 0;
            billingAddress['extension_attributes']['sub_district'] = '';
            billingAddress['extension_attributes']['sub_district_id'] = 0;
            if (billingAddress.customAttributes !== undefined) {
                $.each(billingAddress.customAttributes, function(index, attribute) {
                    if (attribute.attribute_code !== undefined && attribute.attribute_code === 'city_id') {
                        // in case of new address
                        billingAddress['extension_attributes']['city_id'] = attribute.value;
                    } else if (index == 'city_id') {
                        // in case of old address
                        billingAddress['extension_attributes']['city_id'] = attribute;
                    }
                    if (attribute.attribute_code !== undefined && attribute.attribute_code === 'sub_district') {
                        // in case of new address
                        billingAddress['extension_attributes']['sub_district'] = attribute.value;
                    } else if (index == 'sub_district') {
                        // in case of old address
                        billingAddress['extension_attributes']['sub_district'] = attribute;
                    }
                    if (attribute.attribute_code !== undefined && attribute.attribute_code === 'sub_district_id') {
                        // in case of new address
                        billingAddress['extension_attributes']['sub_district_id'] = attribute.value;
                    } else if (index == 'sub_district_id') {
                        // in case of old address
                        billingAddress['extension_attributes']['sub_district_id'] = attribute;
                    }
                });
            }

            // pass execution to original action
            return originalAction(billingAddress);
        });
    };
});
