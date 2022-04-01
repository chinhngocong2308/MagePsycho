<?php

namespace MagePsycho\RegionCityPro\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface CityRepositoryInterface
{
    /**
     * Get City details by City id
     *
     * @param int $id
     * @return Data\CityInterface
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function getById($id);

    /**
     * Save city
     *
     * @param Data\CityInterface $city
     * @return Data\CityInterface
     * @throws CouldNotSaveException
     * @throws LocalizedException
     */
    public function save(Data\CityInterface $city);

    /**
     * Get List of City Id
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return Data\CitySearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete City
     *
     * @param Data\CityInterface $city
     * @return true
     * @throws CouldNotDeleteException
     */
    public function delete(Data\CityInterface $city);

    /**
     * Delete City By Id
     *
     * @param int $id
     * @return true
     * @throws CouldNotDeleteException
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function deleteById($id);
}
