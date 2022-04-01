<?php

namespace MagePsycho\RegionCityPro\Ui\Model;

use Magento\Ui\DataProvider\AbstractDataProvider;
use MagePsycho\RegionCityPro\Model\ResourceModel\City\Collection as CityCollection;
use MagePsycho\RegionCityPro\Model\ResourceModel\City\CollectionFactory as CityCollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class CityDataProvider extends AbstractDataProvider
{
    /**
     * @var CityCollection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CityCollectionFactory $cityCollectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $cityCollectionFactory->create()->load();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->meta = $this->prepareMeta($this->meta);
    }

    /**
     * Prepares Meta
     *
     * @param array $meta
     * @return array
     */
    public function prepareMeta(array $meta)
    {
        return $meta;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();

        /** @var $city \MagePsycho\RegionCityPro\Model\City */
        foreach ($items as $city) {
            $this->loadedData[$city->getId()] = $city->getData();
            $localeNames = $city->getLocaleNames();
            if (! empty($localeNames)) {
                $this->loadedData[$city->getId()]['city_locales'] = $city->getLocaleNames();
            }
        }

        $data = $this->dataPersistor->get('magepsycho_regioncitypro_city');

        if (! empty($data)) {
            $city = $this->collection->getNewEmptyItem();
            $city->setData($data);
            $this->loadedData[$city->getId()] = $city->getData();
            $this->dataPersistor->clear('magepsycho_regioncitypro_city');
        }
        return $this->loadedData;
    }
}
