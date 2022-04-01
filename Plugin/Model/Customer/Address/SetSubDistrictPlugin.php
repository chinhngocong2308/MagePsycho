<?php

namespace MagePsycho\RegionCityPro\Plugin\Model\Customer\Address;

use Magento\Customer\Model\Address;
use MagePsycho\RegionCityPro\Model\SubDistrict;
use MagePsycho\RegionCityPro\Model\SubDistrictFactory;
use MagePsycho\RegionCityPro\Helper\Data as RegionCityProHelper;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class SetSubDistrictPlugin
{
    /**
     * Directory sub district models
     *
     * @var SubDistrict[]
     */
    protected static $subDistrictModels = [];

    /**
     * @var SubDistrictFactory
     */
    protected $subDistrictFactory;

    /**
     * @var RegionCityProHelper
     */
    protected $regionCityProHelper;

    public function __construct(
        SubDistrictFactory $subDistrictFactory,
        RegionCityProHelper $regionCityProHelper
    ) {
        $this->subDistrictFactory = $subDistrictFactory;
        $this->regionCityProHelper = $regionCityProHelper;
    }

    /**
     * Update sub district name as per locale
     *
     * @param Address $subject
     * @param $addressDataObject
     * @return mixed
     */
    public function afterGetDataModel(
        Address $subject,
        $addressDataObject
    ) {
        if ($this->regionCityProHelper->isFxnSkipped()) {
            return $addressDataObject;
        }
        return $addressDataObject;

        // @todo determine the use case first before usage
        if ($subDistrictObject = $addressDataObject->getCustomAttribute('sub_district')) {
            $subDistrictObject->setData('value',
                $this->getSubDistrict($subDistrictObject->getValue(), $subject->getSubDistrictId())
            );
        }
        return $addressDataObject;
    }

    public function getSubDistrict($subDistrict, $subDistrictId)
    {
        if ($subDistrictId) {
            $subDistrict = $this->getSubDistrictModel($subDistrictId)->getName();
        }

        return $subDistrict;
    }

    public function getSubDistrictModel($subDistrictId)
    {
        if (! isset(self::$subDistrictModels[$subDistrictId])) {
            $subDistrict = $this->_createSubDistrictInstance();
            $subDistrict->load($subDistrictId);
            self::$subDistrictModels[$subDistrictId] = $subDistrict;
        }

        return self::$subDistrictModels[$subDistrictId];
    }

    protected function _createSubDistrictInstance()
    {
        return $this->subDistrictFactory->create();
    }
}
