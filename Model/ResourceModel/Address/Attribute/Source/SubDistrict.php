<?php

namespace MagePsycho\RegionCityPro\Model\ResourceModel\Address\Attribute\Source;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory as AttributeOptionCollectionFactory;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory as AttributeOptionFactory;
use MagePsycho\RegionCityPro\Model\ResourceModel\SubDistrict\CollectionFactory as SubDistrictCollectionFactory;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class SubDistrict extends \Magento\Eav\Model\Entity\Attribute\Source\Table
{
    /**
     * @var SubDistrictCollectionFactory
     */
    private $subDistrictCollectionFactory;

    public function __construct(
        AttributeOptionCollectionFactory $attrOptionCollectionFactory,
        AttributeOptionFactory $attrOptionFactory,
        SubDistrictCollectionFactory $subDistrictCollectionFactory
    ) {
        parent::__construct($attrOptionCollectionFactory, $attrOptionFactory);
        $this->subDistrictCollectionFactory = $subDistrictCollectionFactory;
    }

    public function getAllOptions($withEmpty = true, $defaultValues = false)
    {
        if (! $this->_options) {
            $this->_options = $this->createSubDistrictCollection()->load()->toOptionArray();
        }
        return $this->_options;
    }

    protected function createSubDistrictCollection()
    {
        return $this->subDistrictCollectionFactory->create();
    }
}
