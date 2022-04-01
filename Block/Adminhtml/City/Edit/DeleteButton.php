<?php

namespace MagePsycho\RegionCityPro\Block\Adminhtml\City\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    public function getButtonData()
    {
        $data = [];
        if ($this->getId()) {
            $data = [
                'label'     => __('Delete City'),
                'class'     => 'delete',
                'on_click'  => "deleteConfirm('" . __(
                    'Are you sure you want to do this?'
                ) . "', '" . $this->getDeleteUrl() . "')",
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', ['city_id' => $this->getId()]);
    }
}
