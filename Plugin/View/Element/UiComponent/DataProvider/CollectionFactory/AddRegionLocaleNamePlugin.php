<?php

namespace MagePsycho\RegionCityPro\Plugin\View\Element\UiComponent\DataProvider\CollectionFactory;

use Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory;
use Magento\Framework\Data\CollectionDataSourceInterface as Collection;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class AddRegionLocaleNamePlugin
{
    public function afterGetReport(CollectionFactory $subject, Collection $collection, $requestName)
    {
        if ($requestName !== 'regioncitypro_region_listing_data_source') {
            return $collection;
        }

        $collection->getSelect()->joinLeft(
            ['locale_table' => $collection->getResource()->getTable('directory_country_region_name')],
            'locale_table.region_id = main_table.region_id',
            [
                'region_locales' => new \Zend_Db_Expr(
                    "GROUP_CONCAT(
                        DISTINCT CONCAT(
                            '<div class=\"grid-locale-names-col\">',
                            locale_table.locale,
                            ': ',
                            locale_table.name,
                            '</div>'
                        ) SEPARATOR ''
                    )"
                )
            ]
        );

        $collection->getSelect()->group('main_table.region_id');

        return $collection;
    }
}
