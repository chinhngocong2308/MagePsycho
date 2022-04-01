<?php

namespace MagePsycho\RegionCityPro\Model\Export;

use MagePsycho\RegionCityPro\Model\Import\Region as ImportRegion;
use MagePsycho\RegionCityPro\Model\Config\Source\Locale as LocaleSource;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Region
{
    /**
     * Permanent entity columns
     *
     * @var string[]
     */
    protected $_permanentAttributes = [
        ImportRegion::COL_COUNTRY_ID,
        ImportRegion::COL_CODE,
        ImportRegion::COL_DEFAULT_NAME,
    ];

    /**
     * @var array
     */
    protected $headerAttributes = [];

    /**
     * @var LocaleSource
     */
    protected $localeSource;

    public function __construct(
        LocaleSource $localeSource
    ) {
        $this->localeSource = $localeSource;
    }

    /**
     * Get header columns
     *
     * @return string[]
     */
    public function getHeaderColumns()
    {
        $this->headerAttributes = $this->_permanentAttributes;
        // Add dynamic columns
        $locales = $this->localeSource->getOptionsArray();
        foreach ($locales as $locale => $name) {
            $this->headerAttributes = array_merge(
                $this->headerAttributes,
                [ImportRegion::LOCALE_PREFIX . ':' . $locale]
            );
        }
        return $this->headerAttributes;
    }

    /**
     * Prepare & return the data for the export
     *
     * @param $item
     * @return array
     */
    public function getRowData($item)
    {
        $rowData = [
            ImportRegion::COL_COUNTRY_ID => $item->getData(ImportRegion::COL_COUNTRY_ID),
            ImportRegion::COL_CODE => $item->getData(ImportRegion::COL_CODE),
            ImportRegion::COL_DEFAULT_NAME => $item->getData(ImportRegion::COL_DEFAULT_NAME),
        ];
        // prepare default values for locales
        $locales = $this->localeSource->getOptionsArray();
        foreach ($locales as $locale => $name) {
            $rowData = array_merge(
                $rowData,
                [ImportRegion::LOCALE_PREFIX . ':' . $locale => '']
            );
        }

        // prepare locale names
        preg_match_all('/<div class="(.*?)">(.*?)<\/div>/im', $item->getData('region_locales'), $matches);
        if (isset($matches[2])) {
            foreach ($matches[2] as $localeName) {
                list($_locale, $_name) = explode(': ', $localeName);
                if (isset($rowData[ImportRegion::LOCALE_PREFIX . ':' . $_locale])) {
                    $rowData[ImportRegion::LOCALE_PREFIX . ':' . $_locale] = $_name;
                }
            }
        }
        return $rowData;
    }
}
