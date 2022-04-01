<?php

namespace MagePsycho\RegionCityPro\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface CitySearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get List of City
     *
     * @return CityInterface[]
     */
    public function getItems();

    /**
     * Set List of City
     *
     * @param CityInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
