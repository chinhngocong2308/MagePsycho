<?php

namespace MagePsycho\RegionCityPro\Model\Import\Region\Validator;

use MagePsycho\RegionCityPro\Model\Import\Region\RowValidatorInterface;
use MagePsycho\RegionCityPro\Model\Import\Region as ImportRegion;
use Magento\Directory\Model\ResourceModel\Country\CollectionFactory as CountryCollectionFactory;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Country extends AbstractImportValidator implements RowValidatorInterface
{
    /**
     * @var CountryCollectionFactory
     */
    private $countryCollectionFactory;

    private $countries;

    public function __construct(
        CountryCollectionFactory $countryCollectionFactory
    ) {
        $this->countryCollectionFactory = $countryCollectionFactory;
        $this->initCountries();
    }

    private function initCountries()
    {
        if ($this->countries === null) {
            $countryArray = $this->countryCollectionFactory->create()->toOptionArray(false);
            $this->countries = array_combine(
                array_column($countryArray, 'value'),
                array_column($countryArray, 'label')
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isValid($value)
    {
        $this->_clearMessages();
        if (! isset($value[ImportRegion::COL_COUNTRY_ID])) {
            return false;
        }

        $countryId = $value[ImportRegion::COL_COUNTRY_ID];
        if (! strlen($countryId)) {
            $this->_addMessages([self::ERROR_COUNTRY_IS_EMPTY]);
            return false;
        }

        if (! $this->isCountryIdValid($countryId)) {
            $this->_addMessages([self::ERROR_INVALID_COUNTRY]);
            return false;
        }

        return true;
    }

    private function isCountryIdValid($countryId)
    {
        return isset($this->countries[$countryId]);
    }
}
