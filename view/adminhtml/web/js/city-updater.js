define([
    'jquery',
    'prototype',
    'mage/adminhtml/events',
    'mage/adminhtml/form'
], function (jQuery, Prototype, Events, Form) {
    window.CityUpdater = Class.create();
    CityUpdater.prototype = {
        initialize: function (countryEl, regionSelectEl, regionTextEl, cityTextEl, citySelectEl, citiesConfig, disableAction) {
            this.countryEl = $(countryEl);
            this.regionSelectEl = $(regionSelectEl);
            this.regionTextEl = $(regionTextEl);
            this.cityTextEl = $(cityTextEl);
            this.citySelectEl = $(citySelectEl);
            this.citiesConfig = citiesConfig;
            this.sameAsBillingConfig = jQuery('#order-shipping_same_as_billing');

            this.disableAction = typeof disableAction === 'undefined' ? 'hide' : disableAction;

            if (this.citySelectEl.options.length <= 1) {
                this.update();
                this.cityUpdate();
            } else {
                this.lastRegionSelectId = this.regionSelectEl.value;
            }

            this.regionTextEl.changeUpdater = this.update.bind(this);
            this.regionSelectEl.changeUpdater = this.update.bind(this);
            if(this.sameAsBillingConfig.length){
                this.sameAsBillingConfig = this.update.bind(this);
            }
            this.sameAsBillingConfig.changeUpdater = this.update.bind(this);
            if(this.sameAsBillingConfig.length) {
                Event.observe(this.sameAsBillingConfig, 'change', this.cityUpdate.bind(this));
            }
            Event.observe(this.regionSelectEl, 'change', this.update.bind(this));
            Event.observe(this.countryEl, 'change', this.update.bind(this));
            /** Field change the value fill **/
            Event.observe(this.citySelectEl, 'change', this.cityUpdate.bind(this));
            Event.observe(this.cityTextEl, 'change', this.cityUpdate.bind(this));

        },
        update: function () {
            var option, city, def, cityId;

            //typeof this.config == 'undefined'
            if (this.citiesConfig[this.regionSelectEl.value] == undefined) {

                if (this.disableAction == 'hide') { //eslint-disable-line eqeqeq
                    if (this.cityTextEl) {
                        this.cityTextEl.style.display = '';
                        this.cityTextEl.style.disabled = false;
                    }
                    this.citySelectEl.style.display = 'none';
                    this.citySelectEl.disabled = true;


                } else if (this.disableAction == 'disable') { //eslint-disable-line eqeqeq
                    if (this.cityTextEl) {
                        this.cityTextEl.disabled = false;
                    }
                    this.citySelectEl.disabled = true;


                } else if (this.disableAction == 'nullify') { //eslint-disable-line eqeqeq
                    this.citySelectEl.options.length = 1;
                    this.citySelectEl.value = '';
                    this.citySelectEl.selectedIndex = 0;
                    this.lastRegionSelectId = '';
                }
                this.setMarkDisplay(this.citySelectEl, false);

            } else {
                if (this.lastRegionSelectId != this.regionSelectEl.value) {
                    def = this.citySelectEl.getAttribute('defaultValue');
                    if (this.cityTextEl) {
                        if (!def) {
                            def = this.cityTextEl.value.toLowerCase();
                        }
                        this.cityTextEl.value = '';
                    }

                    this.citySelectEl.options.length = 1;

                    for (cityId in this.citiesConfig[this.regionSelectEl.value]) {

                        city = this.citiesConfig[this.regionSelectEl.value][cityId];

                        option = document.createElement('OPTION');
                        option.value = cityId;
                        option.text = city.name.stripTags();
                        option.title = city.name;
                        if (this.citySelectEl.options.add) {
                            this.citySelectEl.options.add(option);
                        } else {
                            this.citySelectEl.appendChild(option);
                        }
                        if (cityId == def
                            || city.name.toLowerCase() == def
                            || (city.code && city.code.toLowerCase() == def)
                        ) {
                            this.citySelectEl.value = cityId;
                        }
                    }
                    //disable city text
                    /* OLD CODE
                    this.cityTextEl.style.display = 'none';
                    this.citySelectEl.style.display = 'block';
                    this._makeCitySelectRequired();
                    */
                }

                if (this.disableAction == 'hide') { //eslint-disable-line eqeqeq
                    if (this.cityTextEl) {
                        this.cityTextEl.style.display = 'none';
                        this.cityTextEl.style.disabled = true;
                    }
                    this.citySelectEl.style.display = '';
                    this.citySelectEl.disabled = false;
                } else if (this.disableAction == 'disable') { //eslint-disable-line eqeqeq
                    if (this.cityTextEl) {
                        this.cityTextEl.disabled = true;
                    }
                    this.citySelectEl.disabled = false;
                }

                this.setMarkDisplay(this.citySelectEl, true);
                this.lastRegionSelectId = this.regionSelectEl.value;

            }
            //varienGlobalEvents.fireEvent('address_country_changed', this.countryEl);
            this._checkRegionRequired();
        },
        _makeCityTextRequired: function () {
            this.cityTextEl.addClassName('required-entry');
            this.citySelectEl.style.display = 'none';

            if (this.citySelectEl.hasClassName('validate-select')) {
                this.citySelectEl.removeClassName('validate-select');
            }
            if (this.citySelectEl.hasClassName('required-entry')) {
                this.citySelectEl.removeClassName('required-entry');
            }

        },
        _makeCitySelectRequired: function () {
            this.citySelectEl.addClassName('required-entry').addClassName('validate-select');
            if (this.cityTextEl.hasClassName('required-entry')) {
                this.cityTextEl.removeClassName('required-entry');
            }
        },
        /**
         * @param {HTMLElement} elem
         * @param {*} display
         */
        setMarkDisplay: function (elem, display) {
            var marks;

            if (elem.parentNode.parentNode) {
                marks = Element.select(elem.parentNode.parentNode, '.required');

                if (marks[0]) {
                    display ? marks[0].show() : marks[0].hide();
                }
            }
        },
        _checkRegionRequired: function () {
            var label, wildCard, elements, that, cityRequired = true;
            elements = [this.cityTextEl, this.citySelectEl];
            that = this;
            elements.each(function (currentElement) {
                var form, validationInstance, field, topElement;
                if (!currentElement) {
                    return;
                }
                form = currentElement.form;
                validationInstance = form ? jQuery(form).data('validation') : null;
                field = currentElement.up('.field') || new Element('div');

                if (validationInstance) {
                    validationInstance.clearError(currentElement);
                }
                label = $$('label[for="' + currentElement.id + '"]')[0];
                if (label) {
                    wildCard = label.down('em') || label.down('span.required');
                    topElement = label.up('tr') || label.up('li');
                }
                if (label && wildCard) {
                    wildCard.show();
                }
                if (!currentElement.visible()) {
                    if (field.hasClassName('required')) {
                        field.removeClassName('required');
                    }

                    if (currentElement.hasClassName('required-entry')) {
                        currentElement.removeClassName('required-entry');
                    }

                    if (currentElement.tagName.toLowerCase() == 'select' && //eslint-disable-line eqeqeq
                        currentElement.hasClassName('validate-select')
                    ) {
                        currentElement.removeClassName('validate-select');
                    }
                } else {
                    if (!field.hasClassName('required')) {
                        field.addClassName('required');
                    }

                    if (!currentElement.hasClassName('required-entry')) {
                        currentElement.addClassName('required-entry');
                    }

                    if (currentElement.tagName.toLowerCase() == 'select' && //eslint-disable-line eqeqeq
                        !currentElement.hasClassName('validate-select')
                    ) {
                        currentElement.addClassName('validate-select');
                    }
                }
            });
        },
        cityUpdate: function () {
            if (this.citySelectEl.visible()) {
                if (this.citySelectEl.options[this.citySelectEl.selectedIndex].value !== '') {
                    this.cityTextEl.value = this.citySelectEl.options[this.citySelectEl.selectedIndex].text;
                } else {
                    this.cityTextEl.value = '';
                }

            } else {
                this.citySelectEl.selected = false;
            }
        }
    }
});
