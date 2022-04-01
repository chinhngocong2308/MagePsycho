<?php

namespace MagePsycho\RegionCityPro\Model\Import\Source\Behavior;

use Magento\ImportExport\Model\Import;
use Magento\ImportExport\Model\Source\Import\AbstractBehavior;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Custom extends AbstractBehavior
{
    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            Import::BEHAVIOR_APPEND   => __('Add/Update'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        return 'mp_directory_region';
    }
}
