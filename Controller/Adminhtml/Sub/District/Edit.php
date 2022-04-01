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
class Edit extends SubDistrictController
{
    public function execute()
    {
        /** @var SubDistrict $model */
        $model = $this->subDistrictFactory->create();
        if ($id = $this->getRequest()->getParam('sub_district_id')) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This sub district no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->registry->register('current_sub_district', $model);

        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Sub District') : __('New Sub District'),
            $id ? __('Edit Sub District') : __('New Sub District')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Sub Districts'));
        $resultPage->getConfig()->getTitle()->prepend(
            $model->getId() ? $model->getDefaultName() : __('New Sub District')
        );

        return $resultPage;
    }
}
