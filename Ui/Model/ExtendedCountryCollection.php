<?php

namespace MagePsycho\RegionCityPro\Ui\Model;

use Magento\Directory\Model\ResourceModel\Country\CollectionFactory as CountryCollectionFactory;
use Magento\Directory\Model\ResourceModel\Region\CollectionFactory as RegionCollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ExtendedCountryCollection implements OptionSourceInterface
{
    /**
     * @var CountryCollectionFactory
     */
    protected $countryCollectionFactory;

    /**
     * @var RegionCollectionFactory
     */
    protected $regionCollectionFactory;

    /**
     * @var array
     */
    protected $options;

    public function __construct(
        CountryCollectionFactory $countryCollectionFactory,
        RegionCollectionFactory $regionCollectionFactory
    ) {
        $this->countryCollectionFactory = $countryCollectionFactory;
        $this->regionCollectionFactory  = $regionCollectionFactory;
    }

    protected function getAvailableCountryFromRegion()
    {
        $regionCollection = $this->regionCollectionFactory->create();
        $regionCollection->addFieldToSelect('country_id');
        $regionCollection->getSelect()->group('country_id');
        return $regionCollection->getColumnValues('country_id');
    }

    /**
     * Convert collection items to select options array
     * Only collect countries with regions
     *
     * @return array
     */
    public function toOptionArray()
    {
        if ($this->options !== null) {
            return $this->options;
        }

        $countryIds = $this->getAvailableCountryFromRegion();
        $collection = $this->countryCollectionFactory->create();
        $collection->addCountryIdFilter($countryIds)->load();

        $options = [];
        foreach ($collection as $item) {
            $option = [];
            $option['value'] = $item->getCountryId();
            $option['label'] = $item->getName();
            $options[] = $option;
        }

        $this->sortByKey($options, 'label');

        $this->options = $options;
        if (!empty($options)) {
            array_unshift(
                $options,
                ['title' => '', 'value' => '', 'label' => __('Please select a country.')]
            );
        }
        return $options;
    }

    private function sortByKey(&$data, $key)
    {
        usort($data, function ($a, $b) use ($key) {
            return strcmp($a[$key], $b[$key]);
        });
    }
}
