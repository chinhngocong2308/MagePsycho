<?php

namespace MagePsycho\RegionCityPro\Block\Adminhtml\Customer\Address\Edit;

use Magento\Backend\Block\Template;
use Magento\Framework\View\LayoutInterface;
use MagePsycho\RegionCityPro\Helper\Data as RegionCityProHelper;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Js extends Template
{
    const CUSTOMER_ADDRESS_EDIT_BLOCK_NAME = 'customer_address_edit';

    /**
     * @var LayoutInterface
     */
    private $currentLayout;

    /**
     * @var RegionCityProHelper
     */
    private $regionCityProHelper;

    public function __construct(
        Template\Context $context,
        RegionCityProHelper $regionCityProHelper,
        $data = []
    ) {
        parent::__construct($context, $data);
        $this->regionCityProHelper = $regionCityProHelper;
        $this->currentLayout       = $context->getLayout();
    }

    public function toHtml()
    {
        if (! $this->regionCityProHelper->getConfigHelper()->isEnabled()) {
            return '';
        }

        return parent::_toHtml();
    }

    public function isActive()
    {
        return $this->regionCityProHelper->getConfigHelper()->isEnabled();
    }

    public function getCityJson()
    {
        return $this->regionCityProHelper->getCityJson();
    }
}
