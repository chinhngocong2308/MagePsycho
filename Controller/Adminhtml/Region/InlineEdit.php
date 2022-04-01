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
class InlineEdit extends RegionController
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
                foreach (array_keys($postItems) as $regionId) {
                    /** @var Region $region */
                    $region = $this->regionFactory->create()->load($regionId);
                    try {
                        $region->setData(array_merge($region->getData(), $postItems[$regionId]));
                        $region->save();
                    } catch (\Exception $e) {
                        $messages[] = $this->getErrorWithRegionId(
                            $region,
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
     * @param Region $region
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithRegionId(Region $region, $errorText)
    {
        return '[Region ID: ' . $region->getId() . '] ' . $errorText;
    }
}
