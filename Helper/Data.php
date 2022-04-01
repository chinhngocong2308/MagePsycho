<?php

namespace MagePsycho\RegionCityPro\Helper;

use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Directory\Model\ResourceModel\Region\CollectionFactory as RegionCollectionFactory;
use Magento\Framework\App\Cache\Type\Config as CacheConfig;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\Module\ModuleListInterface;
use Magento\Store\Model\StoreManagerInterface;
use MagePsycho\RegionCityPro\Helper\Config as ConfigHelper;
use MagePsycho\RegionCityPro\Logger\Logger;
use MagePsycho\RegionCityPro\Model\ResourceModel\City\CollectionFactory as CityCollectionFactory;
use MagePsycho\RegionCityPro\Model\ResourceModel\SubDistrict\CollectionFactory as SubDistrictCollectionFactory;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var Logger
     */
    private $moduleLogger;

    /**
     * @var ConfigHelper
     */
    private $configHelper;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ModuleListInterface
     */
    private $moduleList;

    /**
     * @var DirectoryHelper
     */
    private $directoryHelper;

    /**
     * @var RegionCollectionFactory
     */
    private $regCollectionFactory;

    /**
     * @var JsonHelper
     */
    private $jsonHelper;

    /**
     * @var CityCollectionFactory
     */
    private $cityCollectionFactory;

    /**
     * @var SubDistrictCollectionFactory
     */
    private $subDistrictCollectionFactory;

    private $cityJson;
    private $subDistrictJson;

    /**
     * @var CacheConfig
     */
    protected $configCacheType;

    /**
     * @var ProductMetadataInterface
     */
    private $productMetadata;

    /**
     * @var Url
     */
    protected $urlHelper;

    private $mode;
    private $temp;

    public function __construct(
        Context $context,
        Logger $moduleLogger,
        CacheConfig $configCacheType,
        ConfigHelper $configHelper,
        StoreManagerInterface $storeManager,
        JsonHelper $jsonHelper,
        DirectoryHelper $directoryHelper,
        RegionCollectionFactory $regCollectionFactory,
        CityCollectionFactory $cityCollectionFactory,
        SubDistrictCollectionFactory $subDistrictCollectionFactory,
        ModuleListInterface $moduleList,
        ProductMetadataInterface $productMetadata,
        Url $urlHelper
    ) {
        $this->moduleLogger            = $moduleLogger;
        $this->configHelper            = $configHelper;
        $this->storeManager            = $storeManager;
        $this->moduleList              = $moduleList;
        $this->directoryHelper         = $directoryHelper;
        $this->regCollectionFactory    = $regCollectionFactory;
        $this->jsonHelper              = $jsonHelper;
        $this->cityCollectionFactory   = $cityCollectionFactory;
        $this->subDistrictCollectionFactory = $subDistrictCollectionFactory;
        $this->configCacheType         = $configCacheType;
        $this->productMetadata         = $productMetadata;
        $this->urlHelper               = $urlHelper;
        parent::__construct($context);
        $this->_initialize();
    }

    protected function _initialize()
    {
        $field = $this->decodeBase64('ZG9tYWluX3R5cGU=');
        if ($this->configHelper->getConfigValue('magepsycho_regioncitypro/general/' . $field) == 1) {
            $key        = $this->decodeBase64('cHJvZF9saWNlbnNl');
            $this->mode = $this->decodeBase64('cHJvZHVjdGlvbg==');
        } else {
            $key        = $this->decodeBase64('ZGV2X2xpY2Vuc2U=');
            $this->mode = $this->decodeBase64('ZGV2ZWxvcG1lbnQ=');
        }
        $this->temp = $this->configHelper->getConfigValue('magepsycho_regioncitypro/general/' . $key);
    }

    public function getMessage()
    {
        $message = $this->decodeBase64(
            'WW91IGFyZSB1c2luZyB1bmxpY2Vuc2VkIHZlcnNpb24gb2YgJ1JlZ2lvbiAmIENpdHkgTWFuYWdlcicgZXh0ZW5zaW9uIGZvciBkb21haW46IHt7RE9NQUlOfX0uIFBsZWFzZSBlbnRlciBhIHZhbGlkIExpY2Vuc2UgS2V5IGZyb20gU3RvcmVzICZyYXF1bzsgQ29uZmlndXJhdGlvbiAmcmFxdW87IE1hZ2VQc3ljaG8gJnJhcXVvOyBSZWdpb24gJiBDaXR5IE1hbmFnZXIgJnJhcXVvOyBHZW5lcmFsIFNldHRpbmdzICZyYXF1bzsgTGljZW5zZSBLZXkuIElmIHlvdSBkb24ndCBoYXZlIG9uZSwgcGxlYXNlIHB1cmNoYXNlIGEgdmFsaWQgbGljZW5zZSBmcm9tIDxhIGhyZWY9Imh0dHBzOi8vd3d3Lm1hZ2Vwc3ljaG8uY29tIiB0YXJnZXQ9Il9ibGFuayI+d3d3Lm1hZ2Vwc3ljaG8uY29tPC9hPiBvciB5b3UgY2FuIGRpcmVjdGx5IGVtYWlsIHRvIDxhIGhyZWY9Im1haWx0bzppbmZvQG1hZ2Vwc3ljaG8uY29tIj5pbmZvQG1hZ2Vwc3ljaG8uY29tPC9hPi4='
        );
        $message = str_replace('{{DOMAIN}}', $this->getDomain(), $message);

        return $message;
    }

    public function getDomain()
    {
        $domain     = $this->_urlBuilder->getBaseUrl();
        return $this->urlHelper->getBaseDomain($domain);
    }

    public function checkEntry($domain, $serial)
    {
        $salt = sha1($this->decodeBase64('bTItcmVnaW9uY2l0eXBybw=='));
        if (sha1($salt . $domain . $this->mode) == $serial) {
            return true;
        }

        return false;
    }

    public function getDomainFromSystemConfig()
    {
        $websiteCode = $this->_getRequest()->getParam('website');
        $storeCode   = $this->_getRequest()->getParam('store');
        $xmlPath     = 'web/unsecure/base_url';
        if (!empty($storeCode)) {
            $domain = $this->scopeConfig->getValue(
                $xmlPath,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $storeCode
            );
        } elseif (!empty($websiteCode)) {
            $domain = $this->scopeConfig->getValue(
                $xmlPath,
                \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE,
                $websiteCode
            );
        } else {
            $domain = $this->scopeConfig->getValue(
                $xmlPath
            );
        }
        return $domain;
    }

    private function decodeBase64($text)
    {
        return base64_decode($text);
    }

    public function isValid()
    {
        $temp = $this->temp;
        if (!$this->checkEntry($this->getDomain(), $temp)) {
            return false;
        }

        return true;
    }

    public function isFxnSkipped()
    {
        if (($this->isActive() && !$this->isValid()) || !$this->isActive()) {
            return true;
        }
        return false;
    }

    public function getConfigHelper()
    {
        return $this->configHelper;
    }

    public function getExtensionVersion()
    {
        $moduleCode = 'MagePsycho_RegionCityPro';
        $moduleInfo = $this->moduleList->getOne($moduleCode);
        return $moduleInfo['setup_version'];
    }

    public function getMageVersion()
    {
        return $this->productMetadata->getVersion();
    }

    public function getMageEdition()
    {
        return $this->productMetadata->getEdition();
    }

    public function getBaseUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_WEB,
            true
        );
    }

    public function isActive()
    {
        return $this->configHelper->isActive();
    }

    /**
     * Logging Utility
     *
     * @param $message
     * @param bool|false $useSeparator
     */
    public function log($message, $useSeparator = false)
    {
        if ($this->configHelper->isActive()
            && $this->configHelper->getDebugStatus()
        ) {
            if ($useSeparator) {
                $this->moduleLogger->customLog(str_repeat('=', 100));
            }

            $this->moduleLogger->customLog($message);
        }
    }

    public function getCountryData()
    {
        $output = [];
        foreach ($this->directoryHelper->getCountryCollection() as $code => $data) {
            $output[$code] = $data->getName();
        }
        return $output;
    }

    /**
     * Get review statuses as option array
     *
     * @return array
     */
    public function getCountryDataOptionArray()
    {
        $result = [];
        foreach ($this->getCountryData() as $value => $label) {
            $result[] = ['value' => $value, 'label' => $label];
        }
        return $result;
    }

    public function getRegionData()
    {
        $output = [];
        $collection = $this->regCollectionFactory->create();
        $collection->load();
        foreach ($collection as $region) {
            if (!$region->getRegionId()) {
                continue;
            }
            $output[$region->getRegionId()] = (string)__($region->getName());
        }
        return $output;
    }

    /**
     * Get review statuses as option array
     *
     * @return array
     */
    public function getRegionDataOptionArray()
    {
        $result = [];
        foreach ($this->getRegionData() as $value => $label) {
            $result[] = ['value' => $value, 'label' => $label];
        }

        return $result;
    }

    public function getCityData()
    {
        $collection = $this->cityCollectionFactory->create();
        $collection->getSelect()->order(
            new \Zend_Db_Expr('main_table.country_id, main_table.region_id, main_table.default_name ASC')
        );

        $cities = [];
        foreach ($collection as $city) {
            if (!$city->getRegionId() || !$city->getCityId()) {
                continue;
            }

            $cities[$city->getRegionId()][$city->getCityId()] = [
                'code'          => $city->getCode(),
                'name'          => (string)__($city->getName()),
                'country_code'  => $city->getCountryId()
            ];
        }
        return $cities;
    }

    public function getCityJson()
    {
        if (!$this->cityJson) {
            $cacheKey   = 'MAGEPSYCHO_REGIONCITYPRO_CITY_JSON_STORE' . $this->storeManager->getStore()->getId();
            $json       = $this->configCacheType->load($cacheKey);
            if (empty($json)) {
                $cityData = $this->getCityData();
                $json = $this->jsonHelper->jsonEncode($cityData);
                if ($json === false) {
                    $json = 'false';
                }
                $this->configCacheType->save($json, $cacheKey);
            }
            $this->cityJson = $json;
        }
        return $this->cityJson;
    }

    public function getSubDistrictData()
    {
        $collection = $this->subDistrictCollectionFactory->create();
        $collection->getSelect()->order(
            new \Zend_Db_Expr('main_table.country_id, main_table.region_id, main_table.city_id, main_table.default_name ASC')
        );

        $subDistricts = [];
        foreach ($collection as $subDistrict) {
            if (!$subDistrict->getCityId() || !$subDistrict->getSubDistrictId()) {
                continue;
            }

            $subDistricts[$subDistrict->getCityId()][$subDistrict->getSubDistrictId()] = [
                'code'          => $subDistrict->getCode(),
                'name'          => (string)__($subDistrict->getName()),
                'region_id'     => (string)__($subDistrict->getRegionId()),
                'country_code'  => $subDistrict->getCountryId(),
                'postcode'      => $subDistrict->getPostcode(),
            ];
        }
        return $subDistricts;
    }

    public function getSubDistrictJson()
    {
        if (!$this->subDistrictJson) {
            $cacheKey = 'MAGEPSYCHO_REGIONCITYPRO_SUB_DISTRICT_JSON_STORE' . $this->storeManager->getStore()->getId();
            $json = $this->configCacheType->load($cacheKey);
            if (empty($json)) {
                $data = $this->getSubDistrictData();
                $json = $this->jsonHelper->jsonEncode($data);
                if ($json === false) {
                    $json = 'false';
                }
                $this->configCacheType->save($json, $cacheKey);
            }
            $this->subDistrictJson = $json;
        }
        return $this->subDistrictJson;
    }
}
