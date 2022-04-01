<?php

namespace MagePsycho\RegionCityPro\Controller\Adminhtml\City;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\AlreadyExistsException;
use MagePsycho\RegionCityPro\Model\City;
use MagePsycho\RegionCityPro\Controller\Adminhtml\City as CityController;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Save extends CityController
{
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data = $this->getRequest()->getPostValue()) {
            $id = $this->getRequest()->getParam('city_id');
            if (empty($data['city_id'])) {
                $data['city_id'] = null;
            }
            /** @var City $model */
            $model = $this->cityFactory->create();
            $model->load($id);

            if (! empty($data['city_id'])) {
                if (! $model || ! $model->getId()) {
                    $this->messageManager->addErrorMessage(__('This city no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $locales = [];
            if (array_key_exists('city_locales', $data)) {
                $locales = $data['city_locales'];
                unset($data['city_locales']);
            }

            $model->addData($data);

            try {
                $locales = $this->prepareCityLocales($locales);
                $model->setPostLocaleNames($locales['result']);
                $model->save();
                $this->messageManager->addSuccessMessage(__('The city has been saved.'));
                $this->dataPersistor->clear('magepsycho_regioncitypro_city');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['city_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (AlreadyExistsException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->dataPersistor->set('magepsycho_regioncitypro_city', $data);
                return $resultRedirect->setPath('*/*/edit', ['city_id' => $model->getId()]);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong while saving the city.'));
            }
            $data['city_locales'] = $locales['locale_data_persistor'];
            $this->dataPersistor->set('magepsycho_regioncitypro_city', $data);
            return $resultRedirect->setPath('*/*/edit', ['city_id' => $model->getId()]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @param $locales
     * @return array
     */
    private function prepareCityLocales($locales)
    {
        $result = [];
        if (is_array($locales) && ! empty($locales)) {
            $result = array_filter($locales, function ($localeName) {
                return strlen($localeName);
            });
        }

        $allStoreLocales = $this->locale->toOptionArray();
        $localeDataPersistor = [];
        foreach ($allStoreLocales as $store) {
            if (array_key_exists($store['value'], $result)) {
                $localeDataPersistor[$store['value']] = $result[$store['value']];
            } else {
                $localeDataPersistor[$store['value']] = $store['label'];
            }
        }

        return [
            'locale_data_persistor' => $localeDataPersistor,
            'result' => $result
        ];
    }
}
