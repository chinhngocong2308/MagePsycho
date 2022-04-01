<?php

namespace MagePsycho\RegionCityPro\Model\Config\Source;

use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Locale\ListsInterface;
use Magento\Framework\Option\ArrayInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Locale implements ArrayInterface
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var ListsInterface
     */
    private $localeLists;

    public function __construct(
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        ListsInterface $localeLists
    ) {
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->localeLists = $localeLists;
    }

    public function toOptionArray()
    {
        $locales = $this->getAvailableLocales();
        $_localeLists = $this->localeLists->getOptionLocales();
        $result = [];
        foreach ($locales as $eachStoreLocale) {
            foreach ($_localeLists as $locale) {
                if ($locale['value'] == $eachStoreLocale) {
                    $result[] = [
                        'value' => $locale['value'],
                        'label' => $locale['label']
                    ];
                }
            }
        }

        return $result;
    }

    public function getOptionsArray()
    {
        $options = [];
        foreach ($this->toOptionArray() as $option) {
            $options[$option['value']] = $option['label'];
        }
        return $options;
    }

    private function getAvailableLocales()
    {
        $locales = [];
        $stores = $this->storeManager->getStores(true, true);
        foreach ($stores as $storeCode => $store) {
            $locale = $this->scopeConfig->getValue(
                DirectoryHelper::XML_PATH_DEFAULT_LOCALE,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $storeCode
            );
            $locales[$storeCode] = $locale;
        }

        return array_unique($locales);
    }
}
