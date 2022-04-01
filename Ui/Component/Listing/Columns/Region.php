<?php

namespace MagePsycho\RegionCityPro\Ui\Component\Listing\Columns;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Listing\Columns\Column;
use MagePsycho\RegionCityPro\Helper\Data as RegionCityProHelper;
use MagePsycho\RegionCityPro\Model\Config\Source\Region as RegionSource;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Region extends Column implements OptionSourceInterface
{
    /**
     * @var RegionCityProHelper
     */
    protected $regionCityProHelper;
    /**
     * @var RegionSource
     */
    private $region;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        RegionCityProHelper $regionCityProHelper,
        RegionSource $region,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->regionCityProHelper = $regionCityProHelper;
        $this->region = $region;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $options = $this->region->toOptionArray();
        $this->sortByKey($options, 'label');
        return $options;
    }

    private function sortByKey(&$data, $key)
    {
        usort($data, function ($a, $b) use ($key) {
            return strcmp($a[$key], $b[$key]);
        });
    }
}
