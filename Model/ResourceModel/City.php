<?php

namespace MagePsycho\RegionCityPro\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Locale\Resolver;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use MagePsycho\RegionCityPro\Model\Config\Source\Locale;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class City extends AbstractDb
{
    private $cityLocaleTableName;
    private $cityTableName;
    private $regionLocaleTableName;

    /**
     * @var Resolver
     */
    protected $localeResolver;

    /**
     * @var Locale
     */
    protected $locale;

    public function __construct(
        Context $context,
        Resolver $localeResolver,
        Locale $locale,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->localeResolver = $localeResolver;
        $this->locale = $locale;
    }

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_init('directory_country_region_city', 'city_id');
        $this->cityTableName = $this->getTable('directory_country_region_city_name');
    }

    /**
     * Perform operations before object save
     *
     * @param AbstractModel $object
     * @return $this
     * @throws AlreadyExistsException
     */
    protected function _beforeSave(AbstractModel $object)
    {
        if ($this->checkCityExistenceByName($object)) {
            throw new AlreadyExistsException(
                __('City with name "%1" already exists in the region.', $object->getData('default_name'))
            );
        }

        if (strlen($object->getData('code')) && $this->checkCityExistenceByCode($object)) {
            throw new AlreadyExistsException(
                __('City with code "%1" already exists in the region.', $object->getData('code'))
            );
        }
    }

    protected function checkCityExistenceByName($object)
    {
        return $this->checkCityExistenceByField($object, 'default_name');
    }

    protected function checkCityExistenceByCode($object)
    {
        return $this->checkCityExistenceByField($object, 'code');
    }

    protected function checkCityExistenceByField(AbstractModel $object, $field)
    {
        $select = $this->getConnection()->select()
            ->from(['main_table' => $this->getMainTable()])
            ->where('main_table.country_id = ?', $object->getData('country_id'))
            ->where('main_table.region_id = ?', $object->getData('region_id'))
            ->where('main_table.' . $field . ' = ?', $object->getData($field));
        if ($object->getData('city_id')) {
            $select->where('main_table.city_id <> ?', $object->getData('city_id'));
        }
        if ($this->getConnection()->fetchRow($select)) {
            return true;
        }
        return false;
    }

    public function loadByCityCode(
        \MagePsycho\RegionCityPro\Model\City $cityObject,
        $regionId,
        $cityCode
    ) {
        return $this->_loadByRegion($cityObject, $regionId, (string) $cityCode, 'code');
    }

    /**
     * @param \MagePsycho\RegionCityPro\Model\City $object
     * @param int $regionId
     * @param string $value
     * @param string $fieldName
     * @return $this
     * @throws LocalizedException
     */
    private function _loadByRegion($object, $regionId, $value, $fieldName)
    {
        $connection = $this->getConnection();
        $locale     = $this->localeResolver->getLocale();

        $condition = $connection->quoteInto('cname.city_id = city.city_id AND cname = ?', $locale);
        $select = $connection->select()->from(
            ['city' => $this->getMainTable()]
        )->joinLeft(
            ['cname' => $this->cityTableName],
            $condition,
            ['name']
        )->where(
            'city.region_id = ?',
            $regionId
        )->where(
            "city.{$fieldName}",
            $value
        );

        $data = $connection->fetchRow($select);
        if ($data) {
            $object->setData($object);
        }

        $this->_afterLoad($object);
        return $this;
    }

    /**
     * Loads city by city code and region id
     *
     * @param \MagePsycho\RegionCityPro\Model\City $city
     * @param string $cityCode
     * @param string $regionId
     *
     * @return $this
     */
    public function loadByCode(\MagePsycho\RegionCityPro\Model\City $city, $cityCode, $regionId)
    {
        return $this->_loadByRegion($city, $regionId, (string)$cityCode, 'code');
    }

    /**
     * Load data by region id and default region name
     *
     * @param \MagePsycho\RegionCityPro\Model\City $city
     * @param string $cityName
     * @param string $regionId
     * @return $this
     */
    public function loadByName(\MagePsycho\RegionCityPro\Model\City $city, $cityName, $regionId)
    {
        return $this->_loadByRegion($city, $regionId, (string)$cityName, 'default_name');
    }

    protected function _getLoadSelect($field, $value, $object)
    {
        $select         =  parent::_getLoadSelect($field, $value, $object);
        $connection     = $this->getConnection();
        $locale         = $this->localeResolver->getLocale();
        $systemLocale   = \Magento\Framework\AppInterface::DISTRO_LOCALE_CODE;
        $cityField      = $connection->quoteIdentifier($this->getMainTable() . '.' . $this->getIdFieldName());
        $condition      = $connection->quoteInto('lng.locale = ?', $locale);
        $select->joinLeft(
            ['lng' => $this->cityTableName],
            "{$cityField} = lng.city_id AND {$condition}",
            []
        );

        if ($locale != $systemLocale) {
            $nameExpr   = $connection->getCheckSql('lng.city_id IS NULL', 'slng.name', 'lng.name');
            $condition  = $connection->quoteInto('slng.locale = ?', $systemLocale);
            $select->joinLeft(
                ['slng' => $this->cityTableName],
                "{$cityField} = slng.city_id AND {$condition}",
                ['name' => $nameExpr]
            );
        } else {
            $select->columns(['name'], 'lng');
        }
        return $select;
    }

    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $this->saveCityLocales($object);
        return parent::_afterSave($object);
    }

    private function saveCityLocales($city)
    {
        $id = $city->getId();
        $localeNames = $city->getPostLocaleNames();
        if (! is_array($localeNames) || empty($localeNames)) {
            return;
        }

        $oldLocaleNames = $city->getLocaleNames();
        $insert = array_diff_key($localeNames, $oldLocaleNames);
        $delete = array_diff_key($oldLocaleNames, $localeNames);

        $update = array_intersect_key($localeNames, $oldLocaleNames);
        $update = array_diff_assoc($update, $oldLocaleNames);
        $connection = $this->getConnection();

        if (! empty($delete)) {
            $cond = ['locale IN (?)' => array_keys($delete), 'city_id = ?' => $id ];
            $connection->delete($this->getCityLocaleTable(), $cond);
        }
        if (! empty($insert)) {
            $data = [];
            foreach ($insert as $locale => $name) {
                $data[] = [
                    'city_id' => (int) $id,
                    'locale' => $locale,
                    'name' => $name,
                ];
            }
            $connection->insertMultiple($this->getCityLocaleTable(), $data);
        }
        if (! empty($update)) {
            foreach ($update as $locale => $name) {
                $bind = ['name' => $name];
                $where = ['city_id = ?' => (int)$id , 'locale  = ?' => $locale];
                $connection->update($this->getCityLocaleTable(), $bind, $where);
            }
        }
        return $this;
    }

    /**
     * @param $city
     * @return array
     */
    public function getLocaleNames($city)
    {
        $select = $this->getConnection()->select()->from(
            $this->getCityLocaleTable(),
            ['locale', 'name']
        )->where(
            'city_id = :city_id'
        );
        $bind = ['city_id' => (int) $city->getId()];

        return $this->getConnection()->fetchPairs($select, $bind);
    }

    public function getCityLocaleTable()
    {
        if (!$this->cityLocaleTableName) {
            $this->cityLocaleTableName = $this->getTable('directory_country_region_city_name');
        }
        return $this->cityLocaleTableName;
    }

    public function getRegionLocales($regionId)
    {
        $locales = $this->locale->toOptionArray();
        $localeCodes = [];
        foreach ($locales as $locale) {
            $localeCodes[] = $locale['value'];
        }
        $select = $this->getConnection()->select()->from(
            $this->getRegionLocaleTableName(),
            ['locale', 'name']
        )->where(
            'region_id = :region_id'
        )->where(
            'locale IN (?)',
            $localeCodes
        );
        $bind = ['region_id' => (int) $regionId];

        return $this->getConnection()->fetchPairs($select, $bind);
    }

    public function getRegionLocaleTableName()
    {
        if (!$this->regionLocaleTableName) {
            $this->regionLocaleTableName = $this->getTable('directory_country_region_name');
        }
        return $this->regionLocaleTableName;
    }

    public function saveRegionLocales($regionId, $postedLocales)
    {
        try {
            if (! is_array($postedLocales) || empty($postedLocales)) {
                return;
            }

            $postedLocales = array_filter($postedLocales, function ($localeName) {
                return strlen($localeName);
            });
            $oldRegionLocales = $this->getRegionLocales($regionId);
            $insert = array_diff_key($postedLocales, $oldRegionLocales);
            $delete = array_diff_key($oldRegionLocales, $postedLocales);

            $update = array_intersect_key($postedLocales, $oldRegionLocales);
            $update = array_diff_assoc($update, $oldRegionLocales);

            $connection = $this->getConnection();
            if (! empty($delete)) {
                $cond = ['locale IN (?)' => array_keys($delete), 'region_id = ?' => $regionId];
                $connection->delete($this->getRegionLocaleTableName(), $cond);
            }
            if (! empty($insert)) {
                $data = [];
                foreach ($insert as $locale => $name) {
                    $data[] = [
                        'region_id' => (int) $regionId,
                        'locale' => $locale,
                        'name' => $name,
                    ];
                }
                $connection->insertMultiple($this->getRegionLocaleTableName(), $data);
            }

            if (! empty($update)) {
                foreach ($update as $locale => $name) {
                    $bind = ['name' => $name];
                    $where = ['region_id = ?' => (int) $regionId , 'locale = ?' => $locale];
                    $connection->update($this->getRegionLocaleTableName(), $bind, $where);
                }
            }
        } catch (\Exception $exception) {
            throw new LocalizedException(
                __('Unable to save region locales')
            );
        }

        return $this;
    }
}
