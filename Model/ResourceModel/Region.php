<?php

namespace MagePsycho\RegionCityPro\Model\ResourceModel;

use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Model\AbstractModel;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Region extends \Magento\Directory\Model\ResourceModel\Region
{
    /**
     * Perform operations before object save
     *
     * @param AbstractModel $object
     * @return $this
     * @throws AlreadyExistsException
     */
    protected function _beforeSave(AbstractModel $object)
    {
        if ($this->checkRegionExistenceByName($object)) {
            throw new AlreadyExistsException(
                __('Region with name "%1" already exists in the country.', $object->getData('default_name'))
            );
        }

        if ($this->checkRegionExistenceByCode($object)) {
            throw new AlreadyExistsException(
                __('Region with code "%1" already exists in the country.', $object->getData('code'))
            );
        }
    }

    protected function checkRegionExistenceByName($object)
    {
        return $this->checkRegionExistenceByField($object, 'default_name');
    }

    protected function checkRegionExistenceByCode($object)
    {
        return $this->checkRegionExistenceByField($object, 'code');
    }

    protected function checkRegionExistenceByField(AbstractModel $object, $field)
    {
        $select = $this->getConnection()->select()
            ->from(['main_table' => $this->getMainTable()])
            ->where('main_table.country_id = ?', $object->getData('country_id'))
            ->where('main_table.' . $field . ' = ?', $object->getData($field));
        if ($object->getData('region_id')) {
            $select->where('main_table.region_id <> ?', $object->getData('region_id'));
        }
        if ($this->getConnection()->fetchRow($select)) {
            return true;
        }
        return false;
    }
}
