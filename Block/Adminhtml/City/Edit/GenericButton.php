<?php

namespace MagePsycho\RegionCityPro\Block\Adminhtml\City\Edit;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class GenericButton
{
    /**
     * @var \Magento\Backend\Block\Widget\Context
     */
    protected $context;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * Url Builder
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Backend\Block\Widget\Context $context
    ) {
        $this->context      = $context;
        $this->registry     = $registry;
        $this->urlBuilder   = $context->getUrlBuilder();
    }

    public function getId()
    {
        $city = $this->registry->registry('current_city');
        return $city ? $city->getId() : null;
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->urlBuilder->getUrl($route, $params);
    }
}
