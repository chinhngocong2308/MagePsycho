<?php

namespace MagePsycho\RegionCityPro\Ui\Component\Listing\Columns;

use Magento\Directory\Model\Config\Source\Country as CountryDirectory;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Listing\Columns\Column;
use MagePsycho\RegionCityPro\Helper\Data as RegionCityProHelper;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Country extends Column implements OptionSourceInterface
{
    /**
     * @var RegionCityProHelper
     */
    protected $regionCityProHelper;
    /**
     * @var CountryDirectory
     */
    private $country;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        RegionCityProHelper $regionCityProHelper,
        CountryDirectory $country,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->regionCityProHelper = $regionCityProHelper;
        $this->country = $country;
    }

    public function toOptionArray()
    {
        $options = $this->country->toOptionArray(true);
        return $this->formatLabel($options);
    }

    private function formatLabel($options)
    {
        return array_map(function ($value) {
            $value['label'] = $value['label'] . sprintf(' (%s)', $value['value']);
            return $value;
        }, $options);
    }
}
