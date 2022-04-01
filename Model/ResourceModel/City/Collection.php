<?php

namespace MagePsycho\RegionCityPro\Model\ResourceModel\City;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'city_id';

    /**
     * Locale region name table name
     *
     * @var string
     */
    protected $cityTableName;
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
            \MagePsycho\RegionCityPro\Model\City::class,
            \MagePsycho\RegionCityPro\Model\ResourceModel\City::class
        );
        $this->cityTableName = $this->getTable('directory_country_region_city_name');
    }

    /**
     * Initialize select object
     *
     * @return $this
     */
    protected function _initSelect()
    {
        $this->addFilterToMap('city_id', 'main_table.city_id');

        parent::_initSelect();
        $locale = $this->localeResolver->getLocale();

        $this->addBindParam(':locale', $locale);
        $this->getSelect()->joinLeft(
            ['city_tbl' => $this->cityTableName],
            'main_table.city_id = city_tbl.city_id AND city_tbl.locale = :locale',
            ['name']
        );

        /*$this->getSelect()->columns(
            [
                'name' => new \Zend_Db_Expr(
                    'COALESCE(city_tbl.name, main_table.default_name)'
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
            'value'         => 'city_id',
            'title'         => 'default_name',
            'country_id'    => 'country_id',
            'region_id'     => 'region_id'
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
            ['title' => '', 'value' => '', 'label' => __('Please select a city.')]
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
