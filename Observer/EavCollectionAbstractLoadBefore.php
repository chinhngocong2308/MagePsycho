<?php

namespace MagePsycho\RegionCityPro\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use MagePsycho\RegionCityPro\Helper\Data as RegionCityProHelper;
use Magento\Customer\Model\ResourceModel\Address\Collection as AddressCollection;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class EavCollectionAbstractLoadBefore implements ObserverInterface
{
    /**
     * @var RegionCityProHelper
     */
    private $regionCityProHelper;

    public function __construct(
        RegionCityProHelper $regionCityProHelper
    ) {
        $this->regionCityProHelper  = $regionCityProHelper;
    }

    /**
     * Add city_id to the address collection
     *
     * @param Observer $observer
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(Observer $observer)
    {
        $collection = $observer->getEvent()->getCollection();
        if ($collection instanceof AddressCollection) {
            $collection->addAttributeToSelect('city_id');
        }
    }
}
