<?php

namespace MagePsycho\RegionCityPro\Plugin\Model\Quote\Address;

use Magento\Quote\Api\Data\AddressInterface as QuoteAddressInterface;
use Magento\Quote\Model\Quote\Address\ToOrderAddress;
use MagePsycho\RegionCityPro\Helper\Data as RegionCityProHelper;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ToOrderAddressPlugin
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

    public function aroundConvert(
        ToOrderAddress $subject,
        \Closure $proceed,
        QuoteAddressInterface $quoteAddress,
        $data = []
    ) {
        $this->regionCityProHelper->log(__METHOD__, true);
        if ($this->regionCityProHelper->isFxnSkipped()) {
            return $proceed($quoteAddress, $data);
        }

        $orderAddress = $proceed($quoteAddress, $data);
        $cityId = $quoteAddress->getData('city_id');
        if ($cityId) {
            $orderAddress->setData(
                'city_id',
                $cityId
            );
        }
        $subDistrict = $quoteAddress->getData('sub_district');
        if ($subDistrict) {
            $orderAddress->setData(
                'sub_district',
                $subDistrict
            );
        }
        $subDistrictId = $quoteAddress->getData('sub_district_id');
        if ($subDistrictId) {
            $orderAddress->setData(
                'sub_district_id',
                $subDistrictId
            );
        }
        $this->regionCityProHelper->log('$cityId::' . $cityId . ', $subDistrict::' . $subDistrict . ', $subDistrictId::' . $subDistrictId);

        return $orderAddress;
    }
}
