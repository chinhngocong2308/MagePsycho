<?php

namespace MagePsycho\RegionCityPro\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractExtensibleModel;
use MagePsycho\RegionCityPro\Api\Data\CityInterface;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class City extends AbstractExtensibleModel implements CityInterface, IdentityInterface
{
    /**
     * Region Quote Address cache tag
     */
    const CACHE_TAG = 'city_pro_city';

    /**
     * @return  void
     */
    public function _construct()
    {
        $this->_init(\MagePsycho\RegionCityPro\Model\ResourceModel\City::class);
    }

    public function getLocaleNames()
    {
        if (! $this->getId()) {
            return [];
        }

        $localeNames = $this->getData('locale_names');
        if ($localeNames === null) {
            $localeNames = $this->getResource()->getLocaleNames($this);
            $this->setData('locale_names', $localeNames);
        }
        return $localeNames;
    }

    /**
     * Retrieve city name
     *
     * If name is not declared, then default_name is used
     *
     * @return string
     */
    public function getName()
    {
        $name = $this->getData('name');
        if ($name === null) {
            $name = $this->getData('default_name');
        }
        return $name;
    }

    /**
     * @inheritdoc
     */
    public function getCountryId()
    {
        return $this->getData(self::COUNTRY_ID);
    }

    /**
     * @inheritdoc
     */
    public function setCountryId($countryId)
    {
        return $this->setData(self::COUNTRY_ID, $countryId);
    }

    /**
     * @inheritdoc
     */
    public function getRegionId()
    {
        return $this->getData(self::REGION_ID);
    }

    /**
     * @inheritdoc
     */
    public function setRegionId($regionId)
    {
        return $this->setData(self::REGION_ID, $regionId);
    }

    /**
     * @inheritdoc
     */
    public function getCode()
    {
        return $this->getData(self::CODE);
    }

    /**
     * @inheritdoc
     */
    public function setCode($code)
    {
        return $this->setData(self::CODE, $code);
    }

    /**
     * @inheritdoc
     */
    public function getDefaultName()
    {
        return $this->getData(self::DEFAULT_NAME);
    }

    /**
     * @inheritdoc
     */
    public function setDefaultName($defaultName)
    {
        return $this->setData(self::DEFAULT_NAME, $defaultName);
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return string[]
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId(), self::CACHE_TAG . '_' . $this->getId()];
    }

    public function loadByCode($code, $regionId)
    {
        if ($code) {
            $this->_getResource()->loadByCode($this, $code, $regionId);
        }
        return $this;
    }

    public function loadByName($name, $regionId)
    {
        $this->_getResource()->loadByName($this, $name, $regionId);
        return $this;
    }
}
