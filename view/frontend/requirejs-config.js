var config = {
	map: {
		'*': {
			cityUpdater: 'MagePsycho_RegionCityPro/js/city-updater',
			subDistrictUpdater: 'MagePsycho_RegionCityPro/js/sub-district-updater',
			select2: 'MagePsycho_RegionCityPro/js/select2.min',
            'Magento_Checkout/template/shipping-address/address-renderer/default.html': 'MagePsycho_RegionCityPro/template/shipping-address/address-renderer/default.html',
            'Magento_Checkout/template/shipping-information/address-renderer/default.html': 'MagePsycho_RegionCityPro/template/shipping-information/address-renderer/default.html',
            'Magento_Checkout/template/billing-address/details.html': 'MagePsycho_RegionCityPro/template/billing-address/details.html'
		}
	},
	config: {
		mixins: {
            'Magento_Checkout/js/action/create-shipping-address': {
                'MagePsycho_RegionCityPro/js/action/create-shipping-address-mixin': true
            },
            'Magento_Checkout/js/action/select-shipping-address' : {
                'MagePsycho_RegionCityPro/js/action/select-shipping-address-mixin': true
            },
            'Magento_Checkout/js/action/set-shipping-information': {
				'MagePsycho_RegionCityPro/js/action/set-shipping-information-mixin': true
			},
            'Magento_Checkout/js/action/select-billing-address' : {
                'MagePsycho_RegionCityPro/js/action/select-billing-address-mixin': true
            },
            'Magento_Checkout/js/action/set-payment-information': {
                'MagePsycho_RegionCityPro/js/action/set-payment-information-mixin': true
            },
            'Magento_Checkout/js/view/billing-address': {
                'MagePsycho_RegionCityPro/js/view/billing-address-mixin': true
            },
            'Magento_Checkout/js/view/shipping-address/address-renderer/default': {
                'MagePsycho_RegionCityPro/js/view/shipping-address/address-renderer/default-mixin': true
            },
            'Magento_Checkout/js/view/shipping-information/address-renderer/default': {
                'MagePsycho_RegionCityPro/js/view/shipping-information/address-renderer/default-mixin': true
            }
		}
	}
};
