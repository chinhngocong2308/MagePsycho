define([
    'jquery',
    'mage/template',
    'underscore',
    'select2',
    'jquery/ui',
    'mage/validation',
    'Magento_Checkout/js/region-updater'
], function ($, mageTemplate, _, select2) {
    'use strict';
    $.widget('mage.cityUpdater', {
        options: {
            cityTemplate: '<option value="<%- data.value %>" <% if (data.isSelected) { %>selected="selected"<% } %>>' +
                '<%- data.title %>' +
                '</option>',
            isCityRequired: true,
            currentCity: null
        },

        _create: function () {
            var regionList = $(this.options.regionListId),
                regionInput = $(this.options.regionInputId);

            this.currentCityOption = this.options.currentCity;
            this.cityTmpl = mageTemplate(this.options.cityTemplate);

            if ($(regionList).is(":visible")) {
                this._updateCity($(regionList).find('option:selected').val());
            } else {
                this._updateCity(null);
            }

            $(this.options.cityListId).on('change', $.proxy(function (e) {
                    this.setOption = false;
                    this.currentCityOption = $(e.target).val();
                    if ($(e.target).val() != '') {
                        $(this.options.cityInputId).val($(e.target).find('option:selected').text());
                    }
                }, this)
            );

            $(this.options.cityInputId).on('focusout', $.proxy(function () {
                    this.setOption = true;
                }, this)
            );

            this._bindCountryElement();
            this._bindRegionElement();
        },

        _bindCountryElement: function () {
            $(this.options.countryListId).on('change', $.proxy(function (e) {
                if ($(this.options.regionListId) !== 'undefined'
                    && $(this.options.regionListId).find('option:selected').val() != ''
                ) {
                    this._updateCity($(this.options.regionListId).find('option:selected').val());
                } else {
                    this._updateCity(null);
                }
            }, this));
        },

        _bindRegionElement: function () {
            $(this.options.regionListId).on('change', $.proxy(function (e) {
                this._updateCity($(e.target).val());
            }, this));

            $(this.options.regionInputId).on('focusout', $.proxy(function () {
                this._updateCity(null);
            }, this));
        },

        _updateCity: function (regionId) {
            var cityList = $(this.options.cityListId),
                cityInput = $(this.options.cityInputId),
                label = cityList.parent().siblings('label'),
                requiredLabel = cityList.parents('div.field');

            this._clearError();

            // populate city dropdown list if available else use input box
            if (regionId && this.options.cityJson[regionId]) {
                this._removeSelectOptions(cityList);
                $.each(this.options.cityJson[regionId], $.proxy(function (key, value) {
                    this._renderSelectOption(cityList, key, value);
                }, this));

                if (this.currentCityOption && cityList.find('option[value="' + this.currentCityOption + '"]').length > 0) {
                    cityList.val(this.currentCityOption);
                }

                if (this.setOption) {
                    cityList.find('option').filter(function () {
                        return this.text === cityInput.val();
                    }).attr('selected', true);
                }

                if (this.options.isCityRequired) {
                    cityList.addClass('required-entry').removeAttr('disabled');
                    requiredLabel.addClass('required');
                } else {
                    cityList.removeClass('required-entry validate-select').removeAttr('data-validate');
                    requiredLabel.removeClass('required');
                }

                cityList.show();
                cityInput.removeClass('required-entry').hide();
                label.attr('for', cityList.attr('id'));

                if (this.options.isCitySearchable) {
                    $(this.options.cityListId).select2({
                        width: '100%'
                    });
                }
            } else {
                if (this.options.isCityRequired) {
                    cityInput.addClass('required-entry').removeAttr('disabled');
                    requiredLabel.addClass('required');
                } else {
                    requiredLabel.removeClass('required');
                    cityInput.removeClass('required-entry');
                }

                cityList.removeClass('required-entry').prop('disabled', 'disabled').hide();
                cityInput.show();
                label.attr('for', cityInput.attr('id'));

                if (this.options.isCitySearchable) {
                    $(this.options.cityListId).data('select2') && $(this.options.cityListId).data('select2').destroy();
                }
            }
            cityList.attr('defaultvalue', this.options.defaultCityId);
        },

        _removeSelectOptions: function (selectElement) {
            selectElement.find('option').each(function (index) {
                if (index) {
                    $(this).remove();
                }
            });
        },

        _renderSelectOption: function (selectElement, key, value) {
            selectElement.append($.proxy(function () {
                var name = value.name.replace(/[!"#$%&'()*+,.\/:;<=>?@[\\\]^`{|}~]/g, '\\$&'),
                    tmplData,
                    tmpl;

                if (value.code && $(name).is('span')) {
                    key = value.code;
                    value.name = $(name).text();
                }

                tmplData = {
                    value: key,
                    title: value.name,
                    isSelected: false
                };

                if (this.options.defaultCityId === key) {
                    tmplData.isSelected = true;
                }

                tmpl = this.cityTmpl({
                    data: tmplData
                });

                return $(tmpl);
            }, this));
        },

        _clearError: function () {
            var args = ['clearError', this.options.cityListId, this.options.cityInputId];

            if (this.options.clearError && typeof this.options.clearError === 'function') {
                this.options.clearError.call(this);
            } else {
                if (!this.options.form) {
                    this.options.form = this.element.closest('form').length ? $(this.element.closest('form')[0]) : null;
                }

                this.options.form = $(this.options.form);
                this.options.form && this.options.form.data('validator') &&
                this.options.form.validation.apply(this.options.form, _.compact(args));

                // Clean up errors on region & zip fix
                $(this.options.cityInputId).removeClass('mage-error').parent().find('[generated]').remove();
                $(this.options.cityListId).removeClass('mage-error').parent().find('[generated]').remove();
            }
        }
    });
    return $.mage.cityUpdater;
});
