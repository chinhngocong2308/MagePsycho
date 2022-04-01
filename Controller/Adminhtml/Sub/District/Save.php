<?php

namespace MagePsycho\RegionCityPro\Controller\Adminhtml\Sub\District;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\AlreadyExistsException;
use MagePsycho\RegionCityPro\Model\SubDistrict;
use MagePsycho\RegionCityPro\Controller\Adminhtml\SubDistrict as SubDistrictController;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Save extends SubDistrictController
{
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data = $this->getRequest()->getPostValue()) {
            $id = $this->getRequest()->getParam('sub_district_id');
            if (empty($data['sub_district_id'])) {
                $data['sub_district_id'] = null;
            }
            /** @var SubDistrict $model */
            $model = $this->subDistrictFactory->create();
            $model->load($id);

            if (! empty($data['sub_district_id'])) {
                if (! $model || ! $model->getId()) {
                    $this->messageManager->addErrorMessage(__('This sub district no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $locales = [];
            if (array_key_exists('sub_district_locales', $data)) {
                $locales = $data['sub_district_locales'];
                unset($data['sub_district_locales']);
            }

            $model->addData($data);

            try {
                $locales = $this->prepareSubDistrictLocales($locales);
                $model->setPostLocaleNames($locales['result']);
                $model->save();
                $this->messageManager->addSuccessMessage(__('The sub district has been saved.'));
                $this->dataPersistor->clear('magepsycho_regioncitypro_sub_district');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['sub_district_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (AlreadyExistsException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->dataPersistor->set('magepsycho_regioncitypro_sub_district', $data);
                return $resultRedirect->setPath('*/*/edit', ['sub_district_id' => $model->getId()]);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong while saving the sub district.'));
            }
            $data['sub_district_locales'] = $locales['locale_data_persistor'];
            $this->dataPersistor->set('magepsycho_regioncitypro_sub_district', $data);
            return $resultRedirect->setPath('*/*/edit', ['sub_district_id' => $model->getId()]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @param $locales
     * @return array
     */
    private function prepareSubDistrictLocales($locales)
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
