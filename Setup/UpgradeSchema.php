<?php

namespace MagePsycho\RegionCityPro\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();
        if (version_compare($context->getVersion(), '1.0.6', '<')) {
            $this->addSubDistrictAddressAttribute($installer);
        }
        $installer->endSetup();
    }

    private function addSubDistrictAddressAttribute($installer)
    {
        if (! $installer->tableExists('directory_city_sub_district')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('directory_city_sub_district')
            )->addColumn(
                'sub_district_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true , 'nullable' => false, 'unsigned' => true, 'primary' => true],
                'Sub District ID'
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
                'city_id',
                Table::TYPE_INTEGER,
                10,
                ['nullable' => false ,'default' => '0'],
                'City ID'
            )->addColumn(
                'code',
                Table::TYPE_TEXT,
                32,
                ['nullable' => true, 'default' => null],
                'Sub District Code'
            )->addColumn(
                'default_name',
                Table::TYPE_TEXT,
                255,
                [],
                'Sub District Default Name'
            )->addColumn(
                'postcode',
                Table::TYPE_TEXT,
                255,
                [],
                'Postcode'
            )->addIndex(
                $installer->getIdxName('directory_city_sub_district', ['city_id']),
                'city_id'
            )->addIndex(
                $installer->getIdxName('directory_city_sub_district', ['code']),
                'code'
            )->setComment(
                'Sub District'
            );
            $installer->getConnection()->createTable($table);
        }

        if (! $installer->tableExists('directory_city_sub_district_name')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('directory_city_sub_district_name')
            )->addColumn(
                'locale',
                Table::TYPE_TEXT,
                8,
                ['nullable' => false, 'primary' => true, 'default' => false],
                'Locale'
            )->addColumn(
                'sub_district_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true, 'default' => '0'],
                'Sub District Id'
            )->addColumn(
                'name',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Sub District Name'
            )->addIndex(
                $installer->getIdxName('directory_city_sub_district_name', ['sub_district_id']),
                'sub_district_id'
            )->addIndex(
                $installer->getIdxName('directory_city_sub_district_name', ['locale']),
                'locale'
            )->addForeignKey(
                $installer->getFkName(
                    'directory_city_sub_district_name',
                    'sub_district_id',
                    'directory_city_sub_district',
                    'sub_district_id'
                ),
                'sub_district_id',
                $installer->getTable('directory_city_sub_district'),
                'sub_district_id',
                Table::ACTION_CASCADE
            )->setComment(
                'Sub District Name'
            );
            $installer->getConnection()->createTable($table);
        }

        // Add sub_district, sub_district_id columns in customer, quote & order address tables
        $installer->getConnection()->addColumn(
            $installer->getTable('customer_address_entity'),
            'sub_district',
            [
                'type'      => Table::TYPE_TEXT,
                'length'    => 255,
                'comment'   => 'Sub District'
            ]
        );
        $installer->getConnection()->addColumn(
            $installer->getTable('customer_address_entity'),
            'sub_district_id',
            [
                'type'      => Table::TYPE_INTEGER,
                'comment'   => 'Sub District ID'
            ]
        );

        $installer->getConnection()->addColumn(
            $installer->getTable('quote_address'),
            'sub_district',
            [
                'type'      => Table::TYPE_TEXT,
                'length'    => 255,
                'comment'   => 'Sub District'
            ]
        );
        $installer->getConnection()->addColumn(
            $installer->getTable('quote_address'),
            'sub_district_id',
            [
                'type'      => Table::TYPE_INTEGER,
                'comment'   => 'Sub District ID'
            ]
        );

        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order_address'),
            'sub_district',
            [
                'type'      => Table::TYPE_TEXT,
                'length'    => 255,
                'comment'   => 'Sub District'
            ]
        );
        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order_address'),
            'sub_district_id',
            [
                'type'      => Table::TYPE_INTEGER,
                'comment'   => 'Sub District ID'
            ]
        );
    }
}
