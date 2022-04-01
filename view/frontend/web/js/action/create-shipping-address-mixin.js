define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper, quote) {
    'use strict';

    return function (setShippingInformationAction) {
        return wrapper.wrap(setShippingInformationAction, function (originalAction, messageContainer) {
            var address = quote.shippingAddress();
            if (address !== null) {
                var cityIdElem = $("#shipping-new-address-form [name = 'custom_attributes[city_id]'] option:selected");
                var city = cityIdElem.text();
                var cityId = cityIdElem.val();
                messageContainer.city = city;
                messageContainer.city_id = cityId;

                var subDistrictIdElem = $("#shipping-new-address-form [name = 'custom_attributes[sub_district_id]'] option:selected");
                var subDistrict = subDistrictIdElem.text();
                var subDistrictId = subDistrictIdElem.val();
                messageContainer.sub_district = subDistrict;
                messageContainer.sub_district_id = subDistrictId;
            }
            /*if (messageContainer.custom_attributes !== undefined) {
                $.each(messageContainer.custom_attributes , function( key, value ) {
                    messageContainer['custom_attributes'][key] = value;
                });
            }*/
            // pass execution to original action
            return originalAction(messageContainer);
        });
    };
});
