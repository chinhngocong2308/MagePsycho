<?php

namespace MagePsycho\RegionCityPro\Controller\Adminhtml\City;

use MagePsycho\RegionCityPro\Model\City;
use MagePsycho\RegionCityPro\Controller\Adminhtml\City as CityController;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Edit extends CityController
{
    public function execute()
    {
        /** @var City $model */
        $model = $this->cityFactory->create();
        if ($id = $this->getRequest()->getParam('city_id')) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This city no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->registry->register('current_city', $model);

        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit City') : __('New City'),
            $id ? __('Edit City') : __('New City')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Cities'));
        $resultPage->getConfig()->getTitle()->prepend(
            $model->getId() ? $model->getDefaultName() : __('New City')
        );

        return $resultPage;
    }
}
