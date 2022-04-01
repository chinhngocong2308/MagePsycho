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
class InlineEdit extends SubDistrictController
{
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $id) {
                    /** @var SubDistrict $subDistrict */
                    $subDistrict = $this->subDistrictFactory->create()->load($id);
                    try {
                        $subDistrict->setData(array_merge($subDistrict->getData(), $postItems[$id]));
                        $subDistrict->save();
                    } catch (\Exception $e) {
                        $messages[] = $this->getErrorWithSubDistrictId(
                            $subDistrict,
                            __($e->getMessage())
                        );
                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * Add block title to error message
     *
     * @param SubDistrict $subDistrict
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithSubDistrictId(SubDistrict $subDistrict, $errorText)
    {
        return '[Sub District ID: ' . $subDistrict->getId() . '] ' . $errorText;
    }
}
