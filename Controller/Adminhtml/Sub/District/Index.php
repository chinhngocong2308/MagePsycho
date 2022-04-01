<?php

namespace MagePsycho\RegionCityPro\Controller\Adminhtml\Sub\District;

use MagePsycho\RegionCityPro\Controller\Adminhtml\SubDistrict as SubDistrictController;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Index extends SubDistrictController
{
    public function execute()
    {
        $resultPage  = $this->resultPageFactory->create();
        $this->initPage($resultPage)->getConfig()->getTitle()->prepend(__('Manage Sub Districts'));
        return $resultPage;
    }
}
