<?php

namespace MagePsycho\RegionCityPro\Model\Import\Region;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface RowValidatorInterface extends \Magento\Framework\Validator\ValidatorInterface
{
    const ERROR_COUNTRY_IS_EMPTY        = 'countryEmpty';
    const ERROR_INVALID_COUNTRY         = 'invalidCountry';

    const ERROR_CODE_IS_EMPTY            = 'codeEmpty';
    const ERROR_DEFAULT_NAME_IS_EMPTY    = 'defaultNameEmpty';
    const ERROR_INVALID_LOCALE_CODE      = 'invalidLocaleCode';

    const ERROR_INVALID_DATA        = 'invalidData';
    const ERROR_VALUE_IS_REQUIRED   = 'isRequired';

    const LOCALE_PREFIX = 'locale';

    /**
     * Initialize validator
     *
     * @param \MagePsycho\RegionCityPro\Model\Import\Region $context
     * @return $this
     */
    public function init($context);
}
