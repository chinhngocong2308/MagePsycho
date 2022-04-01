<?php

namespace MagePsycho\RegionCityPro\Model\ResourceModel\Address\Attribute\Source;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory as AttributeOptionCollectionFactory;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory as AttributeOptionFactory;
use MagePsycho\RegionCityPro\Model\ResourceModel\City\CollectionFactory as CityCollectionFactory;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class City extends \Magento\Eav\Model\Entity\Attribute\Source\Table
{
    /**
     * @var CityCollectionFactory
     */
    private $cityCollectionFactory;

    public function __construct(
        AttributeOptionCollectionFactory $attrOptionCollectionFactory,
        AttributeOptionFactory $attrOptionFactory,
        CityCollectionFactory $cityCollectionFactory
    ) {
        parent::__construct($attrOptionCollectionFactory, $attrOptionFactory);
        $this->cityCollectionFactory = $cityCollectionFactory;
    }

    public function getAllOptions($withEmpty = true, $defaultValues = false)
    {
        if (! $this->_options) {
            $this->_options = $this->createCityCollection()->load()->toOptionArray();
        }
        return $this->_options;
    }

    protected function createCityCollection()
    {
        return $this->cityCollectionFactory->create();
    }
}
