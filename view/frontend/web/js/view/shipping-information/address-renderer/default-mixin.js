define([
    'jquery',
], function ($) {
    'use strict';

    return function (Component) {
        return Component.extend({
            getCustomAttributeLabel: function (attribute) {
                if (
                    ((attribute.attribute_code !== undefined && attribute.attribute_code == 'city_id')
                        || (attribute.city_id !== undefined)
                    ) || ((attribute.attribute_code !== undefined && attribute.attribute_code == 'sub_district')
                        || (attribute.sub_district !== undefined)
                    ) || ((attribute.attribute_code !== undefined && attribute.attribute_code == 'sub_district_id')
                        || (attribute.sub_district_id !== undefined)
                    )
                ) {
                    return;
                } else {
                    return this._super();
                }
            }
        });
    }
});
