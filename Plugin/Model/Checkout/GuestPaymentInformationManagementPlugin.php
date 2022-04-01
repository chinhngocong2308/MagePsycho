<?php

namespace MagePsycho\RegionCityPro\Plugin\Model\Checkout;

use Magento\Checkout\Model\GuestPaymentInformationManagement;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\PaymentInterface;
use MagePsycho\RegionCityPro\Helper\Data as RegionCityProHelper;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class GuestPaymentInformationManagementPlugin
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

    public function aroundSavePaymentInformationAndPlaceOrder(
        GuestPaymentInformationManagement $subject,
        \Closure $proceed,
        $cartId,
        $email,
        PaymentInterface $paymentMethod,
        AddressInterface $billingAddress = null
    ) {
        $this->regionCityProHelper->log(__METHOD__, true);
        if ($this->regionCityProHelper->isFxnSkipped()) {
            return $proceed($cartId, $email, $paymentMethod, $billingAddress);
        }

        if ($billingAddress) {
            $extensionAttributes = $billingAddress->getExtensionAttributes();
            if ($extensionAttributes) {
                $cityId = (int) $extensionAttributes->getCityId();
                if ($cityId) {
                    $billingAddress->setCityId($cityId);
                }

                $subDistrict = (string) $extensionAttributes->getSubDistrict();
                if ($subDistrict) {
                    $billingAddress->setSubDistrict($subDistrict);
                }

                $subDistrictId = (int) $extensionAttributes->getSubDistrictId();
                if ($subDistrictId) {
                    $billingAddress->setSubDistrictId($subDistrictId);
                }
                $this->regionCityProHelper->log('$cityId::' . $cityId . ', $subDistrict::' . $subDistrict . ', $subDistrictId::' . $subDistrictId);
            }
        }
        return $proceed($cartId, $email, $paymentMethod, $billingAddress);
    }
}
