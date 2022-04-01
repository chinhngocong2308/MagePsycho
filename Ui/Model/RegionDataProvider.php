<?php

namespace MagePsycho\RegionCityPro\Ui\Model;

use Magento\Directory\Model\ResourceModel\Region\Collection as RegionCollection;
use Magento\Directory\Model\ResourceModel\Region\CollectionFactory as RegionCollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use MagePsycho\RegionCityPro\Model\ResourceModel\City as CityResource;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class RegionDataProvider extends AbstractDataProvider
{
    /**
     * @var RegionCollection
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
    /**
     * @var CityResource
     */
    protected $cityResource;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        RegionCollectionFactory $regionCollectionFactory,
        DataPersistorInterface $dataPersistor,
        CityResource $cityResource,
        array $meta = [],
        array $data = []
    ) {
        $this->collection    = $regionCollectionFactory->create()->load();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->meta = $this->prepareMeta($this->meta);
        $this->cityResource = $cityResource;
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
        foreach ($items as $region) {
            $this->loadedData[$region->getId()] = $region->getData();
            $localesName = $this->cityResource->getRegionLocales($region->getId());
            if (! empty($localesName)) {
                $this->loadedData[$region->getId()]['region_locales'] = $localesName;
            }
        }

        $data = $this->dataPersistor->get('magepsycho_regioncitypro_region');
        if (! empty($data)) {
            $region = $this->collection->getNewEmptyItem();
            $region->setData($data);
            $this->loadedData[$region->getId()] = $region->getData();
            $this->dataPersistor->clear('magepsycho_regioncitypro_region');
        }

        return $this->loadedData;
    }
}
