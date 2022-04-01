/**
 * @api
 */
define([
    'jquery',
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/form/element/select'
], function ($, _, registry, Select) {
    'use strict';

    return Select.extend({
        defaults: {
            skipValidation: false,
            customName: '${ $.parentName }.city',
            imports: {
                update: '${ $.parentName }.region_id:value'
            }
        },

        /**
         * Creates input from template, renders it via renderer.
         *
         * @returns {Object} Chainable.
         */
        initInput: function () {
            return this;
        },

        /**
         * Updates on region change
         *
         * @param {String} value
         */
        update: function (value) {
            var source = this.initialOptions,
                field = this.filterBy.field,
                result,
                initValue;

            result = _.filter(source, function (item) {
                return item[field] === value;
            });

            if (result.length > 0 && value != undefined) {
                this.setVisible(true);
                this.toggleInput(false);

                this.setOptions(result);
                let currentValue = this.initialValue;
                initValue = _.filter(result, function (item) {
                    return item.value === currentValue;
                });
                if (initValue.length > 0) {
                    this.value(currentValue);
                }
            } else {
                this.setVisible(false);
                this.toggleValue('');
                this.toggleInput(true);
                this.setOptions([]);
                this.value('');
            }
        },

        /**
         * @inheritDoc
         */
        filter: function (value, field) {
            var region = registry.get(this.parentName + '.' + 'region_id'),
                value = (value == undefined) ? '' : value;
            if (region) {
                this._super(value, field);
            } else if (value == '' && this.provider == 'checkoutProvider') {
                this.setVisible(false);
                this.toggleValue('');
                this.toggleInput(true);
                this.setOptions([]);
                this.value('');
            }
        },

        /**
         * Callback that fires when 'value' property is updated.
         */
        onUpdate: function () {
            this._super();
            var value = this.value(),
                result;
            result = this.indexedOptions[value];
            if (result != undefined) {
                this.toggleValue(result.label);
            } else {
                this.toggleValue('');
            }
        },

        /**
         * Change value for input.
         */
        toggleValue: function (value) {
            registry.get(this.customName, function (input) {
                input.value(value);
            });
        }
    });
});
