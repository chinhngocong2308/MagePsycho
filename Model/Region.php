<?php

namespace MagePsycho\RegionCityPro\Model;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Region extends \Magento\Directory\Model\Region
{
    protected function _construct()
    {
        $this->_init(\MagePsycho\RegionCityPro\Model\ResourceModel\Region::class);
    }
}
