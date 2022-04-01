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
    $.widget('mage.subDistrictUpdater', {
        options: {
            subDistrictTemplate: '<option value="<%- data.value %>" <% if (data.isSelected) { %>selected="selected"<% } %>>' +
                '<%- data.title %>' +
                '</option>',
            isSubDistrictRequired: true,
            currentSubDistrict: null
        },

        _create: function () {
            var cityList = $(this.options.cityListId);
            this.currentSubDistrictOption = this.options.currentSubDistrict;
            this.subDistrictTmpl = mageTemplate(this.options.subDistrictTemplate);

            if ($(cityList).is(":visible")) {
                this._updateSubDistrict($(cityList).find('option:selected').val());
            } else {
                this._updateSubDistrict(null);
            }

            $(this.options.subDistrictListId).on('change', $.proxy(function (e) {
                    this.setOption = false;
                    var subDistrictIdValue = $(e.target).val();
                    this.currentSubDistrictOption = subDistrictIdValue;

                    if (subDistrictIdValue != '') {
                        $(this.options.subDistrictInputId).val($(e.target).find('option:selected').text());

                        // auto populate postcode
                        var cityIdValue = $(this.options.cityListId).find('option:selected').val();
                        var subDistrict = this.options.subDistrictJson[cityIdValue]
                            ? this.options.subDistrictJson[cityIdValue][subDistrictIdValue]
                            : null;
                        if (subDistrict) {
                            $(this.options.postcodeInputId).val(subDistrict.postcode);
                        } else {
                            $(this.options.postcodeInputId).val('');
                        }
                    } else {
                        $(this.options.postcodeInputId).val('');
                    }
                }, this)
            );

            $(this.options.subDistrictInputId).on('focusout', $.proxy(function () {
                    this.setOption = true;
                }, this)
            );

            this._bindCountryElement();
            this._bindRegionElement();
            this._bindCityElement();
        },

        _bindCountryElement: function () {
            $(this.options.countryListId).on('change', $.proxy(function (e) {
                if ($(this.options.cityListId) !== 'undefined'
                    && $(this.options.cityListId).find('option:selected').val() != ''
                ) {
                    this._updateSubDistrict($(this.options.cityListId).find('option:selected').val());
                } else {
                    this._updateSubDistrict(null);
                }
            }, this));
        },

        _bindRegionElement: function () {
            $(this.options.regionListId).on('change', $.proxy(function (e) {
                if ($(this.options.cityListId) !== 'undefined'
                    && $(this.options.cityListId).find('option:selected').val() != ''
                ) {
                    this._updateSubDistrict($(this.options.cityListId).find('option:selected').val());
                } else {
                    this._updateSubDistrict(null);
                }
            }, this));
        },

        _bindCityElement: function () {
            $(this.options.cityListId).on('change', $.proxy(function (e) {
                this._updateSubDistrict($(e.target).val());
            }, this));

            $(this.options.cityInputId).on('focusout', $.proxy(function (e) {
                this._updateSubDistrict(null);
            }, this));
        },

        _updateSubDistrict: function (cityId) {
            var subDistrictList = $(this.options.subDistrictListId),
                subDistrictInput = $(this.options.subDistrictInputId),
                label = subDistrictList.parent().siblings('label'),
                requiredLabel = subDistrictList.parents('div.field');

            this._clearError();

            // populate sub-district dropdown list if available else use input box
            if (cityId && this.options.subDistrictJson[cityId]) {
                this._removeSelectOptions(subDistrictList);
                $.each(this.options.subDistrictJson[cityId], $.proxy(function (key, value) {
                    this._renderSelectOption(subDistrictList, key, value);
                }, this));

                if (this.currentSubDistrictOption && subDistrictList.find('option[value="' + this.currentSubDistrictOption + '"]').length > 0) {
                    subDistrictList.val(this.currentSubDistrictOption);
                }

                if (this.setOption) {
                    subDistrictList.find('option').filter(function () {
                        return this.text === subDistrictInput.val();
                    }).attr('selected', true);
                }

                if (this.options.isSubDistrictRequired) {
                    subDistrictList.addClass('required-entry').removeAttr('disabled');
                    requiredLabel.addClass('required');
                } else {
                    subDistrictList.removeClass('required-entry validate-select').removeAttr('data-validate');
                    requiredLabel.removeClass('required');
                }

                subDistrictList.show();
                subDistrictInput.removeClass('required-entry').hide();
                label.attr('for', subDistrictList.attr('id'));

                if (this.options.isSubDistrictSearchable) {
                    $(this.options.subDistrictListId).select2({
                        width: '100%'
                    });
                }
            } else {
                if (this.options.isSubDistrictRequired) {
                    subDistrictInput.addClass('required-entry').removeAttr('disabled');
                    requiredLabel.addClass('required');
                } else {
                    requiredLabel.removeClass('required');
                    subDistrictInput.removeClass('required-entry');
                }

                subDistrictList.removeClass('required-entry').prop('disabled', 'disabled').hide();
                subDistrictInput.show();
                label.attr('for', subDistrictInput.attr('id'));

                if (this.options.isSubDistrictSearchable) {
                    $(this.options.subDistrictListId).data('select2') && $(this.options.subDistrictListId).data('select2').destroy();
                }
            }
            subDistrictList.attr('defaultvalue', this.options.defaultSubDistrictId);
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

                if (this.options.defaultSubDistrictId === key) {
                    tmplData.isSelected = true;
                }

                tmpl = this.subDistrictTmpl({
                    data: tmplData
                });

                return $(tmpl);
            }, this));
        },

        _clearError: function () {
            var args = ['clearError', this.options.subDistrictListId, this.options.subDistrictInputId];

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
                $(this.options.subDistrictInputId).removeClass('mage-error').parent().find('[generated]').remove();
                $(this.options.subDistrictListId).removeClass('mage-error').parent().find('[generated]').remove();
            }
        }
    });
    return $.mage.subDistrictUpdater;
});
