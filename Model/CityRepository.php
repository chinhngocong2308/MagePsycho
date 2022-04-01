<?php

namespace MagePsycho\RegionCityPro\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use MagePsycho\RegionCityPro\Api\CityRepositoryInterface;
use MagePsycho\RegionCityPro\Api\Data;
use MagePsycho\RegionCityPro\Api\Data\CitySearchResultsInterface;
use MagePsycho\RegionCityPro\Model\ResourceModel\City as CityResource;
use MagePsycho\RegionCityPro\Model\ResourceModel\City\Collection as CityCollection;
use MagePsycho\RegionCityPro\Model\ResourceModel\City\CollectionFactory;
use MagePsycho\RegionCityPro\Api\Data\CitySearchResultsInterfaceFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use MagePsycho\RegionCityPro\Model\ResourceModel\City\CollectionFactory as CityCollectionFactory;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class CityRepository implements CityRepositoryInterface
{
    /**
     * @var CityFactory
     */
    private $cityFactory;

    /**
     * @var CityResource
     */
    private $cityResource;

    /**
     * @var CityCollectionFactory
     */
    private $collectionFactory;

    /**
     * @var CitySearchResultsInterfaceFactory
     */
    private $citySearchResultsInterfaceFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var JoinProcessorInterface
     */
    private $extensionJoinProcessor;

    public function __construct(
        CityFactory $cityFactory,
        CityResource $cityResource,
        CollectionFactory $collectionFactory,
        CitySearchResultsInterfaceFactory $citySearchResultsInterfaceFactory,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionJoinProcessor
    ) {
        $this->cityFactory = $cityFactory;
        $this->cityResource = $cityResource;
        $this->collectionFactory = $collectionFactory;
        $this->citySearchResultsInterfaceFactory = $citySearchResultsInterfaceFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionJoinProcessor = $extensionJoinProcessor;
    }

    /**
     * @inheritdoc
     */
    public function getById($id)
    {
        $city = $this->cityFactory->create();
        $this->cityResource->load($city, $id);
        if (! $city->getId()) {
            throw new NoSuchEntityException(
                __('Unable to find the record with city id %1', $id)
            );
        }
        return $city;
    }

    /**
     * @inheritdoc
     */
    public function save(Data\CityInterface $city)
    {
        try {
            $this->cityResource->save($city);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the city data: %1', $exception->getMessage())
            );
        }
        return $city;
    }

    /**
     * @inheritdoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /**
         * @var CityCollection $collection
         */
        $collection = $this->collectionFactory->create();
        $this->extensionJoinProcessor->process($collection);
        $this->collectionProcessor->process($searchCriteria, $collection);

        /**
         * @var CitySearchResultsInterface $searchResults
         */
        $searchResults = $this->citySearchResultsInterfaceFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @inheritdoc
     */
    public function delete(Data\CityInterface $city)
    {
        try {
            $this->cityResource->delete($city);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __('Could not delete the city: %1', $exception->getMessage())
            );
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function deleteById($id)
    {
        return $this->delete($this->getById($id));
    }
}
