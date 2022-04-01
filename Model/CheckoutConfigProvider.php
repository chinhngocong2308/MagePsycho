<?php

namespace MagePsycho\RegionCityPro\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use MagePsycho\RegionCityPro\Helper\Data as RegionCityProHelper;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class CheckoutConfigProvider implements ConfigProviderInterface
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

    public function getConfig()
    {
        return [
            'regionCityPro' => [
                'enabled' => (int) !$this->regionCityProHelper->isFxnSkipped()
            ]
        ];
    }
}
