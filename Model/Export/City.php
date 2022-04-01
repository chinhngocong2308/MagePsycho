<?php

namespace MagePsycho\RegionCityPro\Model\Export;

use MagePsycho\RegionCityPro\Model\Import\City as ImportCity;
use MagePsycho\RegionCityPro\Model\Config\Source\Locale as LocaleSource;
use MagePsycho\RegionCityPro\Model\Config\Source\Region as RegionSource;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class City
{
    /**
     * Permanent entity columns
     *
     * @var string[]
     */
    protected $_permanentAttributes = [
        ImportCity::COL_COUNTRY_ID,
        ImportCity::COL_REGION,
        ImportCity::COL_CODE,
        ImportCity::COL_DEFAULT_NAME,
    ];

    /**
     * @var array
     */
    protected $headerAttributes = [];

    /**
     * @var LocaleSource
     */
    protected $localeSource;

    /**
     * @var RegionSource
     */
    protected $regionSource;

    public function __construct(
        LocaleSource $localeSource,
        RegionSource $regionSource
    ) {
        $this->localeSource = $localeSource;
        $this->regionSource = $regionSource;
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
                [ImportCity::LOCALE_PREFIX . ':' . $locale]
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
            ImportCity::COL_COUNTRY_ID => $item->getData(ImportCity::COL_COUNTRY_ID),
            ImportCity::COL_REGION => $this->regionSource->getOptionText($item->getData(ImportCity::COL_REGION_ID)),
            ImportCity::COL_CODE => $item->getData(ImportCity::COL_CODE),
            ImportCity::COL_DEFAULT_NAME => $item->getData(ImportCity::COL_DEFAULT_NAME),
        ];
        // prepare default values for locales
        $locales = $this->localeSource->getOptionsArray();
        foreach ($locales as $locale => $name) {
            $rowData = array_merge(
                $rowData,
                [ImportCity::LOCALE_PREFIX . ':' . $locale => '']
            );
        }

        // prepare locale names
        preg_match_all('/<div class="(.*?)">(.*?)<\/div>/im', $item->getData('city_locales'), $matches);
        if (isset($matches[2])) {
            foreach ($matches[2] as $localeName) {
                list($_locale, $_name) = explode(': ', $localeName);
                if (isset($rowData[ImportCity::LOCALE_PREFIX . ':' . $_locale])) {
                    $rowData[ImportCity::LOCALE_PREFIX . ':' . $_locale] = $_name;
                }
            }
        }
        return $rowData;
    }
}
