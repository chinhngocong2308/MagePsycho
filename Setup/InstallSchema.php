<?php

namespace MagePsycho\RegionCityPro\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class InstallSchema implements InstallSchemaInterface
{
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();

        if (! $installer->tableExists('directory_country_region_city')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('directory_country_region_city')
            )->addColumn(
                'city_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true , 'nullable' => false, 'unsigned' => true, 'primary' => true],
                'City ID'
            )->addColumn(
                'country_id',
                Table::TYPE_TEXT,
                4,
                ['nullable' => true ,'default' => '0'],
                'Country ID in ISO-2'
            )->addColumn(
                'region_id',
                Table::TYPE_INTEGER,
                10,
                ['nullable' => false ,'default' => '0'],
                'Region ID'
            )->addColumn(
                'code',
                Table::TYPE_TEXT,
                32,
                ['nullable' => true, 'default' => null],
                'City Code'
            )->addColumn(
                'default_name',
                Table::TYPE_TEXT,
                255,
                [],
                'City Default Name'
            )->addIndex(
                $installer->getIdxName('directory_country_region_city', ['country_id']),
                'country_id'
            )->addIndex(
                $installer->getIdxName('directory_country_region_city', ['region_id']),
                'region_id'
            )->addIndex(
                $installer->getIdxName('directory_country_region_city', ['code']),
                'code'
            )->setComment(
                'Country Region City'
            );
            $installer->getConnection()->createTable($table);
        }

        if (! $installer->tableExists('directory_country_region_city_name')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('directory_country_region_city_name')
            )->addColumn(
                'locale',
                Table::TYPE_TEXT,
                8,
                ['nullable' => false, 'primary' => true, 'default' => false],
                'Locale'
            )->addColumn(
                'city_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true, 'default' => '0'],
                'City Id'
            )->addColumn(
                'name',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'City Name'
            )->addIndex(
                $installer->getIdxName('directory_country_region_city_name', ['city_id']),
                'city_id'
            )->addForeignKey(
                $installer->getFkName(
                    'directory_country_region_city_name',
                    'city_id',
                    'directory_country_region_city',
                    'city_id'
                ),
                'city_id',
                $installer->getTable('directory_country_region_city'),
                'city_id',
                Table::ACTION_CASCADE
            )->setComment(
                'Country Region City Name'
            );
            $installer->getConnection()->createTable($table);
        }

        // Add city_id column in quote & order address tables
        $installer->getConnection()->addColumn(
            $installer->getTable('quote_address'),
            'city_id',
            [
                'type'      => Table::TYPE_INTEGER,
                'comment'   => 'City ID'
            ]
        );
        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order_address'),
            'city_id',
            [
                'type'      => Table::TYPE_INTEGER,
                'comment'   => 'City ID'
            ]
        );

        $installer->endSetup();
    }
}
