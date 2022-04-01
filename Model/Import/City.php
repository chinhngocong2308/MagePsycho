<?php

namespace MagePsycho\RegionCityPro\Model\Import;

use Magento\Eav\Model\Config;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\Stdlib\StringUtils;
use Magento\ImportExport\Helper\Data as ImportHelper;
use Magento\ImportExport\Model\Import;
use Magento\ImportExport\Model\Import as ImportExport;
use Magento\ImportExport\Model\Import\Entity\AbstractEntity;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;
use Magento\ImportExport\Model\ResourceModel\Helper as ResourceHelper;
use Magento\ImportExport\Model\ResourceModel\Import\Data as ImportData;
use MagePsycho\RegionCityPro\Model\Import\City\RowValidatorInterface as ValidatorInterface;
use MagePsycho\RegionCityPro\Model\Config\Source\Locale as LocaleSource;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingError;
use MagePsycho\RegionCityPro\Model\Import\City\Validator;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class City extends AbstractEntity
{
    const ENTITY_CODE = 'mp_directory_city';

    const TABLE_MAIN = 'directory_country_region_city';
    const TABLE_LOCALE = 'directory_country_region_city_name';

    const COL_COUNTRY_ID = 'country_id';
    const COL_REGION = 'region';
    const COL_REGION_ID = 'region_id';
    const COL_CITY_ID = 'city_id';
    const COL_CODE = 'code';
    const COL_DEFAULT_NAME = 'default_name';
    const COL_LOCALE = 'locale';
    const COL_LOCALE_NAME = 'name';

    const LOCALE_PREFIX = 'name';

    const VALIDATOR_REGION = 'validator_region';

    /**
     * If we should check column names
     */
    protected $needColumnCheck = true;

    /**
     * Need to log in import history
     */
    protected $logInHistory = true;

    /**
     * @var array
     */
    protected $_validators = [];

    /**
     * Permanent entity columns.
     */
    protected $_permanentAttributes = [
        self::COL_COUNTRY_ID,
        self::COL_REGION,
        self::COL_CODE,
        self::COL_DEFAULT_NAME,
    ];

    protected $_specialAttributes = [];

    /**
     * Valid column names
     */
    protected $validColumnNames = [
        self::COL_COUNTRY_ID,
        self::COL_REGION,
        self::COL_CODE,
        self::COL_DEFAULT_NAME,
        // other dynamic groups are initiated by constructor
    ];

    /**
     * Validation failure message template definitions.
     *
     * @var array
     */
    protected $_messageTemplates = [
        ValidatorInterface::ERROR_INVALID_DATA               => 'City data is invalid',
        ValidatorInterface::ERROR_COUNTRY_IS_EMPTY           => 'Country is empty',
        ValidatorInterface::ERROR_INVALID_COUNTRY            => 'Country is invalid',
        ValidatorInterface::ERROR_REGION_IS_EMPTY            => 'Region is empty',
        ValidatorInterface::ERROR_INVALID_REGION             => 'Region is invalid',
        ValidatorInterface::ERROR_CODE_IS_EMPTY              => 'Code is empty',
        ValidatorInterface::ERROR_DEFAULT_NAME_IS_EMPTY      => 'Default name is empty',
        ValidatorInterface::ERROR_INVALID_LOCALE_CODE        => 'Locale code is invalid',
    ];

    /**
     * @var AdapterInterface
     */
    protected $connection;

    /**
     * @var ResourceConnection
     */
    private $resource;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var LocaleSource
     */
    private $localeSource;

    public function __construct(
        JsonHelper $jsonHelper,
        ImportHelper $importExportData,
        ImportData $importData,
        Config $config,
        ResourceConnection $resource,
        ResourceHelper $resourceHelper,
        StringUtils $string,
        ProcessingErrorAggregatorInterface $errorAggregator,
        Validator $validator,
        LocaleSource $localeSource,
        City\Validator\Region $regionValidator
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->_importExportData = $importExportData;
        $this->_resourceHelper = $resourceHelper;
        $this->_dataSourceModel = $importData;
        $this->errorAggregator = $errorAggregator;

        $this->validator = $validator;
        $this->validator->init($this);
        $this->_validators[self::VALIDATOR_REGION] = $regionValidator;

        $this->localeSource = $localeSource;
        $this->resource = $resource;
        $this->connection = $resource->getConnection(ResourceConnection::DEFAULT_CONNECTION);
        $this->initMessageTemplates();
        $this->initValidColumnNames();
    }

    /**
     * Init Error Messages
     */
    private function initMessageTemplates()
    {
        foreach (array_merge($this->errorMessageTemplates, $this->_messageTemplates) as $errorCode => $message) {
            $this->getErrorAggregator()->addErrorMessageTemplate($errorCode, $message);
        }
    }

    private function initValidColumnNames()
    {
        $locales = $this->localeSource->getOptionsArray();
        foreach ($locales as $locale => $name) {
            $this->validColumnNames = array_merge(
                $this->validColumnNames,
                [self::LOCALE_PREFIX . ':' . $locale]
            );
        }
    }

    /**
     * Validator object getter.
     *
     * @param string $type
     * @return \MagePsycho\RegionCityPro\Model\Import\City\Validator
     */
    protected function _getValidator($type)
    {
        return $this->_validators[$type];
    }

    /**
     * Entity type code getter.
     *
     * @return string
     */
    public function getEntityTypeCode()
    {
        return self::ENTITY_CODE;
    }

    /**
     * Get valid columns
     *
     * @return array
     */
    public function getValidColumnNames()
    {
        return $this->validColumnNames;
    }

    /**
     * Get available columns
     *
     * @return array
     */
    public function getMainColumns()
    {
        $mainColumns = $this->_permanentAttributes;
        // replace region by region_id
        $index = array_search(self::COL_REGION, $mainColumns);
        if ($index !== false) {
            $mainColumns[$index] = self::COL_REGION_ID;
        }
        return $mainColumns;
    }

    public function validateRow(array $rowData, $rowNum)
    {
        if (isset($this->_validatedRows[$rowNum])) {
            return !$this->getErrorAggregator()->isRowInvalid($rowNum);
        }
        $this->_validatedRows[$rowNum] = true;

        $countryId = $rowData[self::COL_COUNTRY_ID] ?? '';
        $region = $rowData[self::COL_REGION] ?? '';
        $defaultName = $rowData[self::COL_DEFAULT_NAME] ?? '';

        $mainColExists = true;
        if (! strlen($countryId)) {
            $mainColExists = false;
            $this->skipRow($rowNum, ValidatorInterface::ERROR_COUNTRY_IS_EMPTY);
        }
        if (! strlen($region)) {
            $mainColExists = false;
            $this->skipRow($rowNum, ValidatorInterface::ERROR_REGION_IS_EMPTY);
        }
        if (! strlen($defaultName)) {
            $mainColExists = false;
            $this->skipRow($rowNum, ValidatorInterface::ERROR_DEFAULT_NAME_IS_EMPTY);
        }

        $errorLevel = $this->getValidationErrorLevel($mainColExists);

        if (!$this->validator->isValid($rowData)) {
            foreach ($this->validator->getMessages() as $message) {
                $this->skipRow($rowNum, $message, $errorLevel);
            }
        }

        return !$this->getErrorAggregator()->isRowInvalid($rowNum);
    }

    /**
     * Overridden validate data functionality
     * To support <locale>:<code> pattern
     *
     * @return ProcessingErrorAggregatorInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function validateData()
    {
        if (!$this->_dataValidated) {
            $this->getErrorAggregator()->clear();
            // do all permanent columns exist?
            $absentColumns = array_diff($this->_permanentAttributes, $this->getSource()->getColNames());
            $this->addErrors(self::ERROR_CODE_COLUMN_NOT_FOUND, $absentColumns);

            if (ImportExport::BEHAVIOR_DELETE != $this->getBehavior()) {
                // check attribute columns names validity
                $columnNumber = 0;
                $emptyHeaderColumns = [];
                $invalidColumns = [];
                $invalidAttributes = [];
                foreach ($this->getSource()->getColNames() as $columnName) {
                    $columnNumber++;
                    if (!$this->isAttributeParticular($columnName)) {
                        if (trim($columnName) == '') {
                            $emptyHeaderColumns[] = $columnNumber;
                        } elseif (!preg_match('/^[a-z][a-zA-Z0-9_\:]*$/', $columnName)) {
                            $invalidColumns[] = $columnName;
                        } elseif ($this->needColumnCheck && !in_array($columnName, $this->getValidColumnNames())) {
                            $invalidAttributes[] = $columnName;
                        }
                    }
                }
                $this->addErrors(self::ERROR_CODE_INVALID_ATTRIBUTE, $invalidAttributes);
                $this->addErrors(self::ERROR_CODE_COLUMN_EMPTY_HEADER, $emptyHeaderColumns);
                $this->addErrors(self::ERROR_CODE_COLUMN_NAME_INVALID, $invalidColumns);
            }

            if (!$this->getErrorAggregator()->getErrorsCount()) {
                $this->_saveValidatedBunches();
                $this->_dataValidated = true;
            }
        }
        return $this->getErrorAggregator();
    }

    /**
     * Add row as skipped
     *
     * @param int $rowNum
     * @param string $errorCode Error code or simply column name
     * @param string $errorLevel error level
     * @param string|null $colName optional column name
     * @return $this
     */
    private function skipRow(
        $rowNum,
        string $errorCode,
        string $errorLevel = ProcessingError::ERROR_LEVEL_NOT_CRITICAL,
        $colName = null
    ) {
        $this->addRowError($errorCode, $rowNum, $colName, null, $errorLevel);
        $this->getErrorAggregator()
            ->addRowToSkip($rowNum);
        return $this;
    }

    /**
     * Returns errorLevel for validation
     *
     * @param bool $mainColExists
     * @return string
     */
    private function getValidationErrorLevel($mainColExists)
    {
        return !$mainColExists
            ? ProcessingError::ERROR_LEVEL_CRITICAL
            : ProcessingError::ERROR_LEVEL_NOT_CRITICAL;
    }

    protected function _importData()
    {
        if (Import::BEHAVIOR_APPEND == $this->getBehavior()) {
            $this->saveEntity();
        }

        return true;
    }

    public function saveEntity()
    {
        $this->saveAndReplaceEntity();
        return $this;
    }

    protected function saveAndReplaceEntity()
    {
        $behavior = $this->getBehavior();
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $entityList = [];
            foreach ($bunch as $rowNum => $rowData) {
                if (!$this->validateRow($rowData, $rowNum)) {
                    $this->addRowError(ValidatorInterface::ERROR_INVALID_DATA, $rowNum);
                    continue;
                }
                if ($this->getErrorAggregator()->hasToBeTerminated()) {
                    $this->getErrorAggregator()->addRowToSkip($rowNum);
                    continue;
                }

                $entityList[] = $this->prepareEntityRow($rowData);
            }

            // Ignore invalid rows
            foreach ($bunch as $rowNum => $rowData) {
                if ($this->getErrorAggregator()->isRowInvalid($rowNum)) {
                    unset($bunch[$rowNum]);
                }
            }

            if (Import::BEHAVIOR_APPEND == $behavior) {
                $this->saveEntityFinish($entityList);
            }
        }

        return $this;
    }

    protected function prepareEntityRow($rowData)
    {
        $preparedData = [];
        foreach ($rowData as $field => $value) {
            if (in_array($field, $this->getMainColumns())) {
                $preparedData['main'][$field] = $value;
            }

            // prepare region_id
            if ($field == self::COL_REGION) {
                $regionId = $this->getRegionId($rowData[self::COL_COUNTRY_ID], $rowData[self::COL_REGION]);
                $preparedData['main'][self::COL_REGION_ID] = $regionId;
            }

            if (preg_match('/' . self::LOCALE_PREFIX . ':(.*)/', $field, $matches)) {
                $locale = isset($matches[1]) ? trim($matches[1]) : null;
                if (empty($locale) || ! strlen($value)) {
                    continue;
                }

                $preparedData['locale'][$locale] = $value;
            }
        }
        return $preparedData;
    }

    /**
     * Get region id
     *
     * @param int $countryId
     * @param string $region
     * @return int
     */
    protected function getRegionId($countryId, $region)
    {
        $regions = $this->_getValidator(self::VALIDATOR_REGION)->getRegions();
        if (isset($regions[$countryId]) && isset($regions[$countryId][$region])) {
            return $regions[$countryId][$region];
        }
        return false;
    }

    /**
     * Main operation which updates/inserts the data in main & locale table
     *
     * @param array $entityList
     * @return bool
     */
    protected function saveEntityFinish(array $entityList)
    {
        if (empty($entityList)) {
            return false;
        }

        $mainTableName = $this->resource->getTableName(self::TABLE_MAIN);
        $localeTableName = $this->resource->getTableName(self::TABLE_LOCALE);
        foreach ($entityList as $entityData) {
            $mainData = $entityData['main'] ?? [];
            $localeData = $entityData['locale'] ?? [];
            if (! $mainData[self::COL_REGION_ID]) {
                continue;
            }

            if ($cityId = $this->checkIfCityDataExists(
                $mainData[self::COL_COUNTRY_ID],
                $mainData[self::COL_REGION_ID],
                $mainData[self::COL_CODE]
            )) {
                $this->countItemsUpdated += $this->connection->update(
                    $mainTableName,
                    [
                        self::COL_DEFAULT_NAME => $mainData[self::COL_DEFAULT_NAME]
                    ],
                    [
                        $this->connection->quoteInto('city_id = ?', $cityId)
                    ]
                );
            } else {
                $this->countItemsCreated += $this->connection->insertOnDuplicate(
                    $mainTableName,
                    $mainData,
                    $this->getMainColumns()
                );
                $cityId = $this->connection->lastInsertId();
            }

            if (!empty($localeData) && $cityId) {
                foreach ($localeData as $locale => $localeName) {
                    if ($this->checkIfLocaleDataExists($cityId, $locale)) {
                        $this->countItemsUpdated += $this->connection->update(
                            $localeTableName,
                            [
                                self::COL_LOCALE_NAME => $localeName
                            ],
                            [
                                $this->connection->quoteInto('city_id = ?', $cityId),
                                $this->connection->quoteInto('locale = ?', $locale)
                            ]
                        );
                    } else {
                        $insertData = [
                            self::COL_CITY_ID => $cityId,
                            self::COL_LOCALE => $locale,
                            self::COL_LOCALE_NAME => $localeName
                        ];
                        $this->countItemsCreated += $this->connection->insertOnDuplicate(
                            $localeTableName,
                            $insertData,
                            array_keys($insertData)
                        );
                    }
                }
            }
        }
        return true;
    }

    protected function checkIfCityDataExists($countryId, $regionId, $code)
    {
        $select = $this->connection->select()
            ->from($this->resource->getTableName(self::TABLE_MAIN))
            ->where('country_id = ?', $countryId)
            ->where('region_id = ?', $regionId)
            ->where("code <> ''")
            ->where('code = ?', $code);
        $row = $this->connection->fetchRow($select);
        return $row['city_id'] ?? null;
    }

    protected function checkIfLocaleDataExists($regionId, $locale)
    {
        $select = $this->connection->select()
            ->from($this->resource->getTableName(self::TABLE_LOCALE))
            ->where('city_id = ?', $regionId)
            ->where('locale = ?', $locale);
        return $this->connection->fetchRow($select);
    }
}
