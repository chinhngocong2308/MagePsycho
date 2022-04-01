<?php

namespace MagePsycho\RegionCityPro\Helper;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Config
{
    const XML_PATH_ENABLED = 'magepsycho_regioncitypro/general/enabled';
    const XML_PATH_DEBUG = 'magepsycho_regioncitypro/general/debug';

    const XML_PATH_COUNTRY_SEARCHABLE = 'magepsycho_regioncitypro/country/searchable';
    const XML_PATH_REGION_SEARCHABLE = 'magepsycho_regioncitypro/region/searchable';
    const XML_PATH_CITY_SORT_ORDER = 'magepsycho_regioncitypro/city/sort_order';
    const XML_PATH_CITY_SEARCHABLE = 'magepsycho_regioncitypro/city/searchable';
    const XML_PATH_SUB_DISTRICT_SEARCHABLE = 'magepsycho_regioncitypro/sub_district/searchable';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    public function getConfigValue($xmlPath, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $xmlPath,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function isEnabled($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_ENABLED, $storeId);
    }

    public function isActive($storeId = null)
    {
        return $this->isEnabled($storeId);
    }

    public function getDebugStatus($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_DEBUG, $storeId);
    }

    public function getCitySortOrder($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_CITY_SORT_ORDER, $storeId);
    }

    public function isCitySearchable($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_CITY_SEARCHABLE, $storeId);
    }

    public function isCountrySearchable($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_COUNTRY_SEARCHABLE, $storeId);
    }

    public function isRegionSearchable($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_REGION_SEARCHABLE, $storeId);
    }

    public function isSubDistrictSearchable($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_SUB_DISTRICT_SEARCHABLE, $storeId);
    }
}
