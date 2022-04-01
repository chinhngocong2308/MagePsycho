<?php

namespace MagePsycho\RegionCityPro\Model\ResourceModel\Region;

/**
 * Especially used for Admin Grid filter & mass actions
 *
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Collection extends \Magento\Directory\Model\ResourceModel\Region\Collection
{
    protected $_idFieldName = 'region_id';

    protected function _initSelect()
    {
        $this->addFilterToMap('region_id', 'main_table.region_id');
        parent::_initSelect();
        return $this;
    }
}
