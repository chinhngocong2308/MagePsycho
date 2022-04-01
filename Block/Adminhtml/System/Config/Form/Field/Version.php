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
class Version extends \Magento\Config\Block\System\Config\Form\Field
{
    const EXTENSION_URL = 'https://www.magepsycho.com/magento2-region-city-dropdown-manager.html';

    /**
     * @var \MagePsycho\RegionCityPro\Helper\Data
     */
    private $regionCityProHelper;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \MagePsycho\RegionCityPro\Helper\Data $regionCityProHelper
     */
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
        $extensionVersion = $this->regionCityProHelper->getExtensionVersion();
        $extensionTitle   = 'Region City Manager';
        $versionLabel     = sprintf(
            '<a href="%s" title="%s" target="_blank">%s</a>',
            self::EXTENSION_URL,
            $extensionTitle,
            $extensionVersion
        );
        $element->setValue($versionLabel);

        return $element->getValue();
    }
}
