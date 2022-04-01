<?php

namespace MagePsycho\RegionCityPro\Controller\Adminhtml\Region;

use MagePsycho\RegionCityPro\Controller\Adminhtml\Region as RegionController;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class NewAction extends RegionController
{
    public function execute()
    {
        $resultForward = $this->forwardFactory->create();
        $resultForward->forward('edit');
    }
}
