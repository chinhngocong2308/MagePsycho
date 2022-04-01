<?php

namespace MagePsycho\RegionCityPro\Plugin\Model\Quote;

use Magento\Customer\Api\Data\AddressInterface as CustomerAddressInterface;
use Magento\Quote\Api\Data\AddressInterface as QuoteAddressInterface;
use MagePsycho\RegionCityPro\Helper\Data as RegionCityProHelper;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class AddressPlugin
{
    /**
     * @var RegionCityProHelper
     */
    private $regionCityProHelper;

    public function __construct(
        RegionCityProHelper $regionCityProHelper
    ) {
        $this->regionCityProHelper = $regionCityProHelper;
    }

    /**
     * @param QuoteAddressInterface $quoteAddress
     * @param CustomerAddressInterface $customerAddress
     * @return CustomerAddressInterface
     */
    public function afterExportCustomerAddress(
        QuoteAddressInterface $quoteAddress,
        CustomerAddressInterface $customerAddress
    ) {
        $this->regionCityProHelper->log(__METHOD__, true);
        if ($this->regionCityProHelper->isFxnSkipped()) {
            return $customerAddress;
        }

        $cityId = $quoteAddress->getData('city_id');
        if ($cityId) {
            $customerAddress->setCustomAttribute(
                'city_id',
                $cityId
            );
        }

        $subDistrict = (string) $quoteAddress->getData('sub_district');
        if ($subDistrict) {
            $customerAddress->setCustomAttribute(
                'sub_district',
                $subDistrict
            );
        }

        $subDistrictId = (int) $quoteAddress->getData('sub_district_id');
        if ($subDistrictId) {
            $customerAddress->setCustomAttribute(
                'sub_district_id',
                $subDistrictId
            );
        }
        $this->regionCityProHelper->log('$cityId::' . $cityId . ', $subDistrict::' . $subDistrict . ', $subDistrictId::' . $subDistrictId);

        return $customerAddress;
    }
}
