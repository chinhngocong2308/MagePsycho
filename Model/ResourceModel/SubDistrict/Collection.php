<?php

namespace MagePsycho\RegionCityPro\Model\ResourceModel\SubDistrict;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'sub_district_id';

    /**
     * Locale region name table name
     *
     * @var string
     */
    protected $subDistrictTableName;

    /**
     * @var \Magento\Framework\Locale\ResolverInterface
     */
    protected $localeResolver;

    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Framework\Locale\ResolverInterface $localeResolver,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        $this->localeResolver = $localeResolver;
        $this->_resource = $resource;
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
    }

    protected function _construct()
    {
        $this->_init(
            \MagePsycho\RegionCityPro\Model\SubDistrict::class,
            \MagePsycho\RegionCityPro\Model\ResourceModel\SubDistrict::class
        );
        $this->subDistrictTableName = $this->getTable('directory_city_sub_district_name');
    }

    /**
     * Initialize select object
     *
     * @return $this
     */
    protected function _initSelect()
    {
        $this->addFilterToMap('sub_district_id', 'main_table.sub_district_id');

        parent::_initSelect();
        $locale = $this->localeResolver->getLocale();

        $this->addBindParam(':locale', $locale);
        $this->getSelect()->joinLeft(
            ['sub_district_tbl' => $this->subDistrictTableName],
            'main_table.sub_district_id = sub_district_tbl.sub_district_id AND sub_district_tbl.locale = :locale',
            ['name']
        );

        /*$this->getSelect()->columns(
            [
                'name' => new \Zend_Db_Expr(
                    'COALESCE(sub_district_tbl.name, main_table.default_name)'
                )
            ]
        );*/

        return $this;
    }

    /**
     * Convert collection items to select options array
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        $propertyMap = [
            'value'         => 'sub_district_id',
            'title'         => 'default_name',
            'country_id'    => 'country_id',
            'region_id'     => 'region_id',
            'city_id'       => 'city_id',
            'postcode'      => 'postcode'
        ];

        foreach ($this as $item) {
            $option = [];
            foreach ($propertyMap as $code => $field) {
                $option[$code] = $item->getData($field);
            }
            $option['label'] = $item->getName();
            $options[] = $option;
        }

        array_unshift(
            $options,
            ['title' => '', 'value' => '', 'label' => __('Please select a sub district.')]
        );

        return $options;
    }

    /**
     * Add country filter
     * @param array $countryIds
     * @return $this
     */
    public function addCountryFilter($countryIds)
    {
        $this->addFieldToFilter('country_id', $countryIds);
        return $this;
    }
}
