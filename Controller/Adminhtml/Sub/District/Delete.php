<?php

namespace MagePsycho\RegionCityPro\Controller\Adminhtml\Sub\District;

use MagePsycho\RegionCityPro\Model\SubDistrict;
use MagePsycho\RegionCityPro\Controller\Adminhtml\SubDistrict as SubDistrictController;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Delete extends SubDistrictController
{
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id = $this->getRequest()->getParam('sub_district_id')) {
            /** @var SubDistrict $subDistrict */
            $subDistrict = $this->subDistrictFactory->create();
            $subDistrict->load($id);

            if (! $subDistrict->getId()) {
                $this->messageManager->addErrorMessage(__("We can't find a sub district to delete."));
                return $resultRedirect->setPath('*/*/');
            }

            try {
                $subDistrict->delete();
                $this->messageManager->addSuccessMessage(__('The sub district has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
            return $resultRedirect->setPath('*/*/edit', [' sub district _id' => $subDistrict->getId()]);
        }
        $this->messageManager->addErrorMessage(__("We can't find a sub district to delete."));
        return $resultRedirect->setPath('*/*/');
    }
}
