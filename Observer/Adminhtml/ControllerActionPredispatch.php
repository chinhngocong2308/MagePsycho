<?php

namespace MagePsycho\RegionCityPro\Observer\Adminhtml;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;
use MagePsycho\RegionCityPro\Helper\Data as RegionCityProHelper;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ControllerActionPredispatch implements ObserverInterface
{
    /**
     * @var RegionCityProHelper
     */
    protected $regionCityProHelper;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    public function __construct(
        RegionCityProHelper $regionCityProHelper,
        ManagerInterface $messageManager
    ) {
        $this->regionCityProHelper   = $regionCityProHelper;
        $this->messageManager       = $messageManager;
    }

    public function execute(Observer $observer)
    {
        $isValid          = $this->regionCityProHelper->isValid();
        $isActive         = $this->regionCityProHelper->isActive();
        $request          = $observer->getRequest();
        $fullActionName   = $request->getFullActionName();
        if ($isActive
            && !$isValid
            && 'adminhtml_system_config_edit' == $fullActionName
            && 'magepsycho_regioncitypro' == $request->getParam('section')
        ) {
            $this->messageManager->addComplexErrorMessage(
                'magepsychoRegionCityProComplexMessage',
                [
                    'message' => $this->regionCityProHelper->getMessage()
                ]
            );
        }
        return $this;
    }
}
