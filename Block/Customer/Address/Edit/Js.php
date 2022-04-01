<?php

namespace MagePsycho\RegionCityPro\Block\Customer\Address\Edit;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\LayoutInterface;
use MagePsycho\RegionCityPro\Helper\Data as RegionCityProHelper;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Js extends Template
{
    const CUSTOMER_ADDRESS_EDIT_BLOCK_NAME = 'customer_address_edit';

    /**
     * @var LayoutInterface
     */
    private $currentLayout;

    /**
     * @var RegionCityProHelper
     */
    private $regionCityProHelper;

    public function __construct(
        Template\Context $context,
        RegionCityProHelper $regionCityProHelper,
        $data = []
    ) {
        parent::__construct($context, $data);
        $this->regionCityProHelper = $regionCityProHelper;
        $this->currentLayout       = $context->getLayout();
    }

    public function toHtml()
    {
        if ($this->regionCityProHelper->isFxnSkipped()) {
            return '';
        }

        return parent::_toHtml();
    }

    public function isActive()
    {
        return $this->regionCityProHelper->getConfigHelper()->isEnabled();
    }

    public function isCitySearchable()
    {
        return $this->regionCityProHelper->getConfigHelper()->isCitySearchable();
    }

    public function isSubDistrictSearchable()
    {
        return $this->regionCityProHelper->getConfigHelper()->isSubDistrictSearchable();
    }

    public function getCityJson()
    {
        return $this->regionCityProHelper->getCityJson();
    }

    public function getSubDistrictJson()
    {
        return $this->regionCityProHelper->getSubDistrictJson();
    }

    private function getCurrentAddress()
    {
        $customerAddressBlock = $this->currentLayout->getBlock(self::CUSTOMER_ADDRESS_EDIT_BLOCK_NAME);
        if (! $customerAddressBlock) {
            return false;
        }
        return $customerAddressBlock->getAddress();
    }

    public function getCityId()
    {
        $customerAddress = $this->getCurrentAddress();
        if (! $customerAddress || ! $customerAddress->getId()) {
            return 0;
        }

        return $customerAddress->getCustomAttribute('city_id')
            ? $customerAddress->getCustomAttribute('city_id')->getValue()
            : 0;
    }

    public function getSubDistrict()
    {
        $customerAddress = $this->getCurrentAddress();
        if (! $customerAddress || ! $customerAddress->getId()) {
            return '';
        }
        return $customerAddress->getCustomAttribute('sub_district')
            ? $customerAddress->getCustomAttribute('sub_district')->getValue()
            : 0;
    }

    public function getSubDistrictId()
    {
        $customerAddress = $this->getCurrentAddress();
        if (! $customerAddress || ! $customerAddress->getId()) {
            return 0;
        }

        return $customerAddress->getCustomAttribute('sub_district_id')
            ? $customerAddress->getCustomAttribute('sub_district_id')->getValue()
            : 0;
    }
}
