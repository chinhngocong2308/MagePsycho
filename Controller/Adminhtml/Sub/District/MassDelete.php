<?php

namespace MagePsycho\RegionCityPro\Controller\Adminhtml\Sub\District;

use Magento\Framework\Controller\ResultFactory;
use MagePsycho\RegionCityPro\Controller\Adminhtml\SubDistrict as SubDistrictController;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MassDelete extends SubDistrictController
{
    public function execute()
    {
        $collection = $this->filter->getCollection($this->subDistrictCollectionFactory->create());
        $collectionSize = $collection->getSize();

        foreach ($collection as $subDistrict) {
            $subDistrict->delete();
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $collectionSize));

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
