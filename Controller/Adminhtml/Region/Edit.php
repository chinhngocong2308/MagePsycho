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
class Edit extends RegionController
{
    public function execute()
    {
        /** @var Region $model */
        $model = $this->regionFactory->create();
        if ($id = $this->getRequest()->getParam('region_id')) {
            $model->load($id);
            if (! $model->getId()) {
                $this->messageManager->addErrorMessage(__('This region no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->registry->register('current_region', $model);

        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Region') : __('New Region'),
            $id ? __('Edit Region') : __('New Region')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Regions'));
        $resultPage->getConfig()->getTitle()->prepend(
            $model->getId() ? $model->getDefaultName() : __('New Region')
        );
        return $resultPage;
    }
}
