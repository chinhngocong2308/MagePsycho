<?php

namespace MagePsycho\RegionCityPro\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface SubDistrictInterface extends ExtensibleDataInterface
{
    /**#@+
     * Constants
     */
    const ID = 'sub_district_id';
    const COUNTRY_ID = 'country_id';
    const REGION_ID = 'region_id';
    const CITY_ID = 'city_id';
    const CODE = 'code';
    const DEFAULT_NAME = 'default_name';
    /**#@-*/

    /**
     * Get Sub District Id
     *
     * @return int
     */
    public function getId();

    /**
     * Set Sub District Id
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Get Country Id
     *
     * @return string
     */
    public function getCountryId();

    /**
     * Set Country Id
     *
     * @param string $countryId
     * @return $this
     */
    public function setCountryId($countryId);

    /**
     * Get Region Id
     *
     * @return int|null
     */
    public function getRegionId();

    /**
     * Set Region Id
     *
     * @param int $regionId
     * @return $this
     */
    public function setRegionId($regionId);

    /**
     * Get City Id
     *
     * @return int
     */
    public function getCityId();

    /**
     * Set City Id
     *
     * @param int $cityId
     * @return $this
     */
    public function setCityId($cityId);

    /**
     * Get Code
     *
     * @return string
     */
    public function getCode();

    /**
     * Set Code
     *
     * @param string $code
     * @return $this
     */
    public function setCode($code);

    /**
     * Get Default Name
     *
     * @return string
     */
    public function getDefaultName();

    /**
     * Set Default Name
     *
     * @param string $defaultName
     * @return $this
     */
    public function setDefaultName($defaultName);
}
