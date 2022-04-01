<?php

namespace MagePsycho\RegionCityPro\Model\ResourceModel\Address\Attribute\Backend;

use MagePsycho\RegionCityPro\Helper\Data as RegionCityProHelper;
use MagePsycho\RegionCityPro\Model\CityFactory;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class City extends \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
{
    /**
     * @var RegionCityProHelper
     */
    private $regionCityProHelper;

    /**
     * @var CityFactory
     */
    private $cityFactory;

    public function __construct(
        RegionCityProHelper $regionCityProHelper,
        CityFactory $cityFactory
    ) {
        $this->regionCityProHelper  = $regionCityProHelper;
        $this->cityFactory          = $cityFactory;
    }

    public function beforeSave($object)
    {
        $cityId = $object->getData('city_id');
        if ($cityId && is_numeric($cityId)) {
            $city = $this->cityFactory->create();
            $city->load($cityId);
            if ($city->getId() && $object->getRegionId() == $city->getRegionId()) {
                $object->setCityId($city->getId())
                       ->setCity($city->getName());
            }
        } else {
            $object->setData('city_id', null);
        }
        return $this;
    }
}
