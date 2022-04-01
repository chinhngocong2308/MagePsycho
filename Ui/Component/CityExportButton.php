<?php

namespace MagePsycho\RegionCityPro\Ui\Component;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class CityExportButton extends \Magento\Ui\Component\ExportButton
{
    public function prepare()
    {
        $config = $this->getConfig();
        $options = $config['options'];

        if (!array_key_exists('xml', $options)) {
            parent::prepare();
            return;
        }

        unset($options['xml']);
        $config['options'] = $options;
        $this->setConfig($config);
        parent::prepare();
    }
}
