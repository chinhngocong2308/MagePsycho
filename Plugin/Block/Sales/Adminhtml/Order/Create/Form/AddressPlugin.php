<?php

namespace MagePsycho\RegionCityPro\Plugin\Block\Sales\Adminhtml\Order\Create\Form;

use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Block\Adminhtml\Order\Create\Form\Address as FormAddress;
use MagePsycho\RegionCityPro\Block\Adminhtml\Sales\Order\Address\Form\Renderer\CityId as CityIdRenderer;
use MagePsycho\RegionCityPro\Helper\Data as RegionCityProHelper;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class AddressPlugin
{
    /**
     * @var RegionCityProHelper
     */
    private $regionCityProHelper;

    public function __construct(
        RegionCityProHelper $regionCityProHelper
    ) {
        $this->regionCityProHelper = $regionCityProHelper;
    }

    /**
     * @param FormAddress $subject
     * @param $result
     * @return mixed
     * @throws LocalizedException
     */
    public function afterGetForm(
        FormAddress $subject,
        $result
    ) {
        $this->regionCityProHelper->log(__METHOD__);
        $cityIdElement = $result->getElement('city_id');
        $cityElement = $result->getElement('city');
        if ($cityIdElement) {
            $cityIdElement->setNoDisplay(true);
            $cityElement->setRenderer($subject->getLayout()->createBlock(
                CityIdRenderer::class
            ));
        }
        return $result;
    }
}
