<?php

namespace MagePsycho\RegionCityPro\Plugin\Model\Checkout;

use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Model\ShippingInformationManagement;
use MagePsycho\RegionCityPro\Helper\Data as RegionCityProHelper;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ShippingInformationManagementPlugin
{
    /**
     * @var RegionCityProHelper
     */
    private $regionCityProHelper;

    public function __construct(
        RegionCityProHelper $regionCityProHelper
    ) {
        $this->regionCityProHelper  = $regionCityProHelper;
    }

    public function beforeSaveAddressInformation(
        ShippingInformationManagement $subject,
        $cartId,
        ShippingInformationInterface $addressInformation
    ) {
        $this->regionCityProHelper->log(__METHOD__, true);
        if ($this->regionCityProHelper->isFxnSkipped()) {
            return [$cartId, $addressInformation];
        }

        $shippingAddress = $addressInformation->getShippingAddress();
        $extensionAttributes = $shippingAddress->getExtensionAttributes();
        if ($extensionAttributes) {
            $cityId = (int) $extensionAttributes->getCityId();
            if ($cityId) {
                $shippingAddress->setCityId($cityId);
            }

            $subDistrict = (string) $extensionAttributes->getSubDistrict();
            if ($subDistrict) {
                $shippingAddress->setSubDistrict($subDistrict);
            }

            $subDistrictId = (int) $extensionAttributes->getSubDistrictId();
            if ($subDistrictId) {
                $shippingAddress->setSubDistrictId($subDistrictId);
            }
            $this->regionCityProHelper->log('$cityId::' . $cityId . ', $subDistrict::' . $subDistrict . ', $subDistrictId::' . $subDistrictId);
        }
        return [$cartId, $addressInformation];
    }
}
