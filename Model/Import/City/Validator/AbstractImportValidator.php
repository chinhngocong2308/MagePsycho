<?php

namespace MagePsycho\RegionCityPro\Model\Import\City\Validator;

use Magento\Framework\Validator\AbstractValidator;
use MagePsycho\RegionCityPro\Model\Import\City\RowValidatorInterface;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
abstract class AbstractImportValidator extends AbstractValidator implements RowValidatorInterface
{
    /**
     * @var \MagePsycho\RegionCityPro\Model\Import\City
     */
    protected $context;

    /**
     * @param \MagePsycho\RegionCityPro\Model\Import\City $context
     * @return $this|RowValidatorInterface
     */
    public function init($context)
    {
        $this->context = $context;
        return $this;
    }
}
