<?php

namespace MagePsycho\RegionCityPro\Controller\Adminhtml\Region;

use Magento\Directory\Model\Region;
use MagePsycho\RegionCityPro\Controller\Adminhtml\Region as RegionController;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Delete extends RegionController
{
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id = $this->getRequest()->getParam('region_id')) {
            /** @var Region $model */
            $model = $this->regionFactory->create();
            $model->load($id);

            if (! $model->getId()) {
                $this->messageManager->addErrorMessage(__("We can't find a Region to delete."));
                return $resultRedirect->setPath('*/*/');
            }

            try {
                $model->delete();
                $this->messageManager->addSuccessMessage(__('The region has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
            return $resultRedirect->setPath('*/*/edit', ['region_id' => $model->getId()]);
        }

        $this->messageManager->addErrorMessage(__("We can't find a region to delete."));
        return $resultRedirect->setPath('*/*/');
    }
}
