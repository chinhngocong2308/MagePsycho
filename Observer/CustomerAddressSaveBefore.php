<?php

namespace MagePsycho\RegionCityPro\Observer;

use Magento\Customer\Model\Address;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use MagePsycho\RegionCityPro\Helper\Data as RegionCityProHelper;
use Magento\Framework\App\RequestInterface;
use MagePsycho\RegionCityPro\Api\Data\CityInterface;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class CustomerAddressSaveBefore implements ObserverInterface
{
    /**
     * @var RegionCityProHelper
     */
    private $regionCityProHelper;

    /**
     * @var RequestInterface
     */
    private $request;

    public function __construct(
        RegionCityProHelper $regionCityProHelper,
        RequestInterface $request
    ) {
        $this->regionCityProHelper  = $regionCityProHelper;
        $this->request = $request;
    }

    public function execute(Observer $observer)
    {
        if ($this->request->getModuleName() === 'customer'
            && $this->request->getActionName() === 'createpost'
        ) {
            /** @var $customerAddress Address */
            $customerAddress = $observer->getCustomerAddress();
            if ($this->request->getParam(CityInterface::ID)) {
                $customerAddress->setData(CityInterface::ID, $this->request->getParam(CityInterface::ID));
            }
            if ($this->request->getParam('sub_district_id')) {
                $customerAddress->setData('sub_district_id', $this->request->getParam('sub_district_id'));
            }
            if ($this->request->getParam('sub_district')) {
                $customerAddress->setData('sub_district', $this->request->getParam('sub_district'));
            }
        }
    }
}
