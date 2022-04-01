<?php

namespace MagePsycho\RegionCityPro\Ui\Model;

use Magento\Ui\DataProvider\AbstractDataProvider;
use MagePsycho\RegionCityPro\Model\ResourceModel\SubDistrict\Collection as SubDistrictCollection;
use MagePsycho\RegionCityPro\Model\ResourceModel\SubDistrict\CollectionFactory as SubDistrictCollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class SubDistrictDataProvider extends AbstractDataProvider
{
    /**
     * @var SubDistrictCollection
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
        SubDistrictCollectionFactory $subDistrictCollectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $subDistrictCollectionFactory->create()->load();
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

        /** @var $subDistrict \MagePsycho\RegionCityPro\Model\SubDistrict */
        foreach ($items as $subDistrict) {
            $this->loadedData[$subDistrict->getId()] = $subDistrict->getData();
            $localeNames = $subDistrict->getLocaleNames();
            if (! empty($localeNames)) {
                $this->loadedData[$subDistrict->getId()]['sub_district_locales'] = $subDistrict->getLocaleNames();
            }
        }

        $data = $this->dataPersistor->get('magepsycho_regioncitypro_sub_district');

        if (! empty($data)) {
            $subDistrict = $this->collection->getNewEmptyItem();
            $subDistrict->setData($data);
            $this->loadedData[$subDistrict->getId()] = $subDistrict->getData();
            $this->dataPersistor->clear('magepsycho_regioncitypro_sub_district');
        }
        return $this->loadedData;
    }
}
