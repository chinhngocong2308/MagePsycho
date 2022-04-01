<?php

namespace MagePsycho\RegionCityPro\Block\Adminhtml\System\Config\Form\Field;

use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Domain extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @var \MagePsycho\RegionCityPro\Helper\Data
     */
    protected $regionCityProHelper;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \MagePsycho\RegionCityPro\Helper\Data $regionCityProHelper
    ) {
        $this->regionCityProHelper = $regionCityProHelper;
        parent::__construct($context);
    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $domain = $this->regionCityProHelper->getDomainFromSystemConfig();
        $element->setValue($domain);

        return $element->getValue();
    }
}
