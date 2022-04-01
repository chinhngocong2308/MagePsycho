<?php

namespace MagePsycho\RegionCityPro\Model\ResourceModel\Address\Attribute\Backend;

use MagePsycho\RegionCityPro\Helper\Data as RegionCityProHelper;
use MagePsycho\RegionCityPro\Model\SubDistrictFactory;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class SubDistrict extends \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
{
    /**
     * @var RegionCityProHelper
     */
    private $regionCityProHelper;

    /**
     * @var SubDistrictFactory
     */
    private $subDistrictFactory;

    public function __construct(
        RegionCityProHelper $regionCityProHelper,
        SubDistrictFactory $subDistrictFactory
    ) {
        $this->regionCityProHelper = $regionCityProHelper;
        $this->subDistrictFactory = $subDistrictFactory;
    }

    public function beforeSave($object)
    {
        $subDistrictId = $object->getData('sub_district_id');
        if ($subDistrictId && is_numeric($subDistrictId)) {
            $subDistrict = $this->subDistrictFactory->create();
            $subDistrict->load($subDistrictId);
            if ($subDistrict->getId() && $object->getCityId() == $subDistrict->getCityId()) {
                $object->setSubDistrictId($subDistrict->getId())
                    ->setSubDistrict($subDistrict->getName());
            }
        } else {
            $object->setData('sub_district_id', null);
        }
        return $this;
    }
}
