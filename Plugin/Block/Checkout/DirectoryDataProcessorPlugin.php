<?php

namespace MagePsycho\RegionCityPro\Plugin\Block\Checkout;

use Magento\Checkout\Block\Checkout\DirectoryDataProcessor;
use MagePsycho\RegionCityPro\Helper\Data as RegionCityProHelper;
use MagePsycho\RegionCityPro\Model\ResourceModel\City\CollectionFactory as CityCollectionFactory;
use MagePsycho\RegionCityPro\Model\ResourceModel\SubDistrict\CollectionFactory as SubDistrictCollectionFactory;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class DirectoryDataProcessorPlugin
{
    /**
     * @var RegionCityProHelper
     */
    private $regionCityProHelper;

    /**
     * @var CityCollectionFactory
     */
    private $cityCollectionFactory;

    /**
     * @var SubDistrictCollectionFactory
     */
    private $subDistrictCollectionFactory;

    public function __construct(
        RegionCityProHelper $regionCityProHelper,
        CityCollectionFactory $cityCollectionFactory,
        SubDistrictCollectionFactory $subDistrictCollectionFactory
    ) {
        $this->regionCityProHelper = $regionCityProHelper;
        $this->cityCollectionFactory = $cityCollectionFactory;
        $this->subDistrictCollectionFactory = $subDistrictCollectionFactory;
    }

    public function afterProcess(
        DirectoryDataProcessor $subject,
        array $jsLayout
    ) {
        if ($this->regionCityProHelper->isFxnSkipped()) {
            return $jsLayout;
        }

        if (isset($jsLayout['components']['checkoutProvider']['dictionaries'])) {
            $jsLayout['components']['checkoutProvider']['dictionaries']['city_id'] = $this->getCityOptions();
            $jsLayout['components']['checkoutProvider']['dictionaries']['sub_district_id'] = $this->getSubDistrictOptions();
        }
        return $jsLayout;
    }

    private function getCityOptions()
    {
        $options = $this->cityCollectionFactory->create()->toOptionArray();
        $this->sortByKey($options, 'label');
        return $options;
    }

    private function getSubDistrictOptions()
    {
        $options = $this->subDistrictCollectionFactory->create()->toOptionArray();
        $this->sortByKey($options, 'label');
        return $options;
    }

    private function sortByKey(&$data, $key)
    {
        usort($data, function ($a, $b) use ($key) {
            return strcmp($a[$key], $b[$key]);
        });
    }
}
