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
class InlineEdit extends CityController
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
                foreach (array_keys($postItems) as $cityId) {
                    /** @var City $city */
                    $city = $this->cityFactory->create()->load($cityId);
                    try {
                        $city->setData(array_merge($city->getData(), $postItems[$cityId]));
                        $city->save();
                    } catch (\Exception $e) {
                        $messages[] = $this->getErrorWithCityId(
                            $city,
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
     * @param City $city
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithCityId(City $city, $errorText)
    {
        return '[City ID: ' . $city->getId() . '] ' . $errorText;
    }
}
