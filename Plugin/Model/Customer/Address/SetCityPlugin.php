<?php

namespace MagePsycho\RegionCityPro\Plugin\Model\Customer\Address;

use Magento\Customer\Model\Address;
use MagePsycho\RegionCityPro\Model\CityFactory;
use MagePsycho\RegionCityPro\Helper\Data as RegionCityProHelper;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class SetCityPlugin
{
    /**
     * Directory city models
     *
     * @var \MagePsycho\RegionCityPro\Model\City[]
     */
    protected static $_cityModels = [];

    /**
     * @var CityFactory
     */
    protected $cityFactory;

    /**
     * @var RegionCityProHelper
     */
    protected $regionCityProHelper;

    public function __construct(
        CityFactory $cityFactory,
        RegionCityProHelper $regionCityProHelper
    ) {
        $this->cityFactory = $cityFactory;
        $this->regionCityProHelper = $regionCityProHelper;
    }

    /**
     * Update city name as per locale
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

        $addressDataObject->setCity(
            $this->getCity($addressDataObject->getCity(), $subject->getCityId())
        );
        return $addressDataObject;
    }

    public function getCity($city, $cityId)
    {
        if ($cityId) {
            $city = $this->getCityModel($cityId)->getName();
        }

        return $city;
    }

    public function getCityModel($cityId)
    {
        if (! isset(self::$_cityModels[$cityId])) {
            $city = $this->_createCityInstance();
            $city->load($cityId);
            self::$_cityModels[$cityId] = $city;
        }

        return self::$_cityModels[$cityId];
    }

    protected function _createCityInstance()
    {
        return $this->cityFactory->create();
    }
}
