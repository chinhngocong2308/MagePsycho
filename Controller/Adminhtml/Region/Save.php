<?php

namespace MagePsycho\RegionCityPro\Controller\Adminhtml\Region;

use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use MagePsycho\RegionCityPro\Model\Region;
use MagePsycho\RegionCityPro\Model\ResourceModel\City as CityResourceModel;
use MagePsycho\RegionCityPro\Controller\Adminhtml\Region as RegionController;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Save extends RegionController
{
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data = $this->getRequest()->getPostValue()) {
            if (empty($data['region_id'])) {
                $data['region_id'] = null;
            }

            $id = $this->getRequest()->getParam('region_id');
            /** @var Region $model */
            $model = $this->regionFactory->create();
            $model->load($id);

            if (! empty($data['region_id'])) {
                if (! $model || ! $model->getId()) {
                    $this->messageManager->addErrorMessage(__('This region no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->addData($data);
            try {
                $region = $model->save();
                if (array_key_exists('region_locales', $data) && is_array($data['region_locales'])) {
                    $cityResourceModel = $this->_objectManager->create(CityResourceModel::class);
                    $cityResourceModel->saveRegionLocales($region->getId(), $data['region_locales']);
                }

                $this->messageManager->addSuccessMessage(__('The region has been saved.'));
                $this->dataPersistor->clear('magepsycho_regioncitypro_region');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['region_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (AlreadyExistsException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->dataPersistor->set('magepsycho_regioncitypro_region', $data);
                return $resultRedirect->setPath('*/*/edit', ['region_id' => $model->getId()]);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage(
                    $e,
                    __('Something went wrong while saving the region.')
                );
            }
            $this->dataPersistor->set('magepsycho_regioncitypro_region', $data);
            return $resultRedirect->setPath('*/*/edit', ['region_id' => $model->getId()]);
        }

        return $resultRedirect->setPath('*/*/');
    }
}
