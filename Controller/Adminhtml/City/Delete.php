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
class Delete extends CityController
{
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id = $this->getRequest()->getParam('city_id')) {
            /** @var City $city */
            $city = $this->cityFactory->create();
            $city->load($id);

            if (! $city->getId()) {
                $this->messageManager->addErrorMessage(__("We can't find a city to delete."));
                return $resultRedirect->setPath('*/*/');
            }

            try {
                $city->delete();
                $this->messageManager->addSuccessMessage(__('The city has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
            return $resultRedirect->setPath('*/*/edit', ['city_id' => $city->getId()]);
        }
        $this->messageManager->addErrorMessage(__("We can't find a city to delete."));
        return $resultRedirect->setPath('*/*/');
    }
}
