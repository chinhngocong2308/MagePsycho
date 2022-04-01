<?php

namespace MagePsycho\RegionCityPro\Model\Import\City\Validator;

use MagePsycho\RegionCityPro\Model\Import\City\RowValidatorInterface;
use MagePsycho\RegionCityPro\Model\Import\City as ImportCity;
use Magento\Directory\Model\ResourceModel\Region\CollectionFactory as RegionCollectionFactory;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Region extends AbstractImportValidator implements RowValidatorInterface
{
    /**
     * @var RegionCollectionFactory
     */
    private $regionCollectionFactory;

    private $regions;

    public function __construct(
        RegionCollectionFactory $regionCollectionFactory
    ) {
        $this->regionCollectionFactory = $regionCollectionFactory;
        $this->initRegions();
    }

    private function initRegions()
    {
        if ($this->regions === null) {
            $collection = $this->regionCollectionFactory->create();
            $collection->load();
            $regions = [];
            foreach ($collection as $region) {
                if (! $region->getCountryId() || ! $region->getRegionId() || ! $region->getName()) {
                    continue;
                }
                $regions[$region->getCountryId()][$region->getName()] = $region->getRegionId();
            }
            $this->regions = $regions;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isValid($value)
    {
        $this->_clearMessages();
        if (! isset($value[ImportCity::COL_COUNTRY_ID])) {
            return false;
        }
        if (! isset($value[ImportCity::COL_REGION])) {
            return false;
        }

        $countryId = $value[ImportCity::COL_COUNTRY_ID];
        if (! strlen($countryId)) {
            $this->_addMessages([self::ERROR_COUNTRY_IS_EMPTY]);
            return false;
        }

        $region = $value[ImportCity::COL_REGION];
        if (! strlen($region)) {
            $this->_addMessages([self::ERROR_REGION_IS_EMPTY]);
            return false;
        }

        if (! $this->isCountryIdValid($countryId)) {
            $this->_addMessages([self::ERROR_INVALID_COUNTRY]);
            return false;
        }

        if (! $this->isRegionValid($countryId, $region)) {
            $this->_addMessages([self::ERROR_INVALID_REGION]);
            return false;
        }

        return true;
    }

    public function getRegions()
    {
        if ($this->regions === null) {
            $this->initRegions();
        }
        return $this->regions;
    }

    private function isCountryIdValid($countryId)
    {
        return isset($this->regions[$countryId]);
    }

    private function isRegionValid($countryId, $region)
    {
        return isset($this->regions[$countryId]) && isset($this->regions[$countryId][$region]);
    }
}
