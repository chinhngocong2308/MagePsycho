<?php

namespace MagePsycho\RegionCityPro\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;
use MagePsycho\RegionCityPro\Model\ResourceModel\City\CollectionFactory as CityCollectionFactory;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class City implements ArrayInterface
{
    protected $_options;

    /**
     * @var CityCollectionFactory
     */
    private $cityCollectionFactory;

    public function __construct(
        CityCollectionFactory $cityCollectionFactory
    ) {
        $this->cityCollectionFactory = $cityCollectionFactory;
    }

    public function getAllOptions($withEmpty = false)
    {
        if ($this->_options === null) {
            $_options = [];
            $regionCollection = $this->cityCollectionFactory->create();
            $regionCollection->getSelect()->order('country_id ASC');
            foreach ($regionCollection as $region) {
                $_options[] = [
                    'value' => $region->getId(),
                    'label' => $region->getName()
                ];
            }
            $this->_options = $_options;
        }

        $options = $this->_options;
        if ($withEmpty) {
            array_unshift($options, ['value' => '', 'label' => '']);
        }
        return $options;
    }

    public function getOptionsArray($withEmpty = true)
    {
        $options = [];
        foreach ($this->getAllOptions($withEmpty) as $option) {
            $options[$option['value']] = $option['label'];
        }
        return $options;
    }

    public function getOptionText($value)
    {
        $options = $this->getAllOptions(false);
        foreach ($options as $item) {
            if ($item['value'] == $value) {
                return $item['label'];
            }
        }
        return false;
    }

    public function toOptionArray()
    {
        return $this->getAllOptions();
    }

    public function toOptionHash($withEmpty = true)
    {
        return $this->getOptionsArray($withEmpty);
    }
}
