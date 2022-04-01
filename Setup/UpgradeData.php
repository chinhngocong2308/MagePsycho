<?php

namespace MagePsycho\RegionCityPro\Setup;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Eav\Api\AttributeRepositoryInterface;
use MagePsycho\RegionCityPro\Model\ResourceModel\Address\Attribute\Backend\SubDistrict as SubDistrictBackend;
use MagePsycho\RegionCityPro\Model\ResourceModel\Address\Attribute\Source\SubDistrict as SubDistrictSource;
use Magento\Customer\Api\AddressMetadataInterface;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;

    /**
     * @var AttributeRepositoryInterface
     */
    private $attributeRepository;

    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;

    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        AttributeRepositoryInterface $attributeRepository,
        AttributeSetFactory $attributeSetFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeRepository  = $attributeRepository;
        $this->attributeSetFactory  = $attributeSetFactory;
    }

    public function upgrade(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $setup->startSetup();
        if (version_compare($context->getVersion(), '1.0.6', '<')) {
            $this->addSubDistrictAddressAttribute($setup);
        }
        $setup->endSetup();
    }

    private function addSubDistrictAddressAttribute($setup)
    {
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        $addressAttributes = [
            'sub_district' =>  [
                'label' => 'Sub District',
                'input' => 'text',
                'type' => 'static',
                'source' => '',
                'backend' => '',
                'sort_order' => 105,
                'visible' => true,
            ],
            'sub_district_id' =>  [
                'label' => 'Sub District',
                'input' => 'select',
                'type' => 'static',
                'source' => SubDistrictSource::class,
                'backend' => SubDistrictBackend::class,
                'sort_order' => 110,
                'visible' => true,
            ],
        ];

        foreach ($addressAttributes as $attributeCode => $addressAttribute) {
            if ($this->checkIfAddressAttributeExists($attributeCode)) {
                continue;
            }
            $customerSetup->addAttribute(
                AddressMetadataInterface::ENTITY_TYPE_ADDRESS,
                $attributeCode,
                [
                    'label'                 => $addressAttribute['label'],
                    'input'                 => $addressAttribute['input'],
                    'type'                  => $addressAttribute['type'],
                    'required'              => false,
                    'visible'               => $addressAttribute['visible'],
                    'system'                => false,
                    'user_defined'          => true,
                    'is_used_in_grid'       => false,
                    'is_visible_in_grid'    => false,
                    'is_filterable_in_grid' => false,
                    'is_searchable_in_grid' => false,
                    'source'                => $addressAttribute['source'],
                    'backend'               => $addressAttribute['backend'],
                    'sort_order'            => $addressAttribute['sort_order'],
                    'position'              => $addressAttribute['sort_order'],
                ]
            );

            $attributeSetId     = $customerSetup->getEavConfig()
                ->getEntityType(AddressMetadataInterface::ENTITY_TYPE_ADDRESS)
                ->getDefaultAttributeSetId();
            $attributeSet       = $this->attributeSetFactory->create();
            $attributeGroupId   = $attributeSet->getDefaultGroupId($attributeSetId);
            $attribute = $customerSetup->getEavConfig()
                ->clear()
                ->getAttribute(
                    AddressMetadataInterface::ENTITY_TYPE_ADDRESS,
                    $attributeCode
                )
                ->addData([
                    'attribute_set_id'      => $attributeSetId,
                    'attribute_group_id'    => $attributeGroupId,
                    'used_in_forms'          => [
                        'adminhtml_customer_address',
                        'customer_register_address',
                        'customer_address_edit'
                    ],
                ]);
            $attribute->save();
        }
    }

    /**
     * @param $attributeCode
     *
     * @return bool
     */
    private function checkIfAddressAttributeExists($attributeCode)
    {
        try {
            return (bool) $this->attributeRepository->get(
                AddressMetadataInterface::ENTITY_TYPE_ADDRESS,
                $attributeCode
            );
        } catch (NoSuchEntityException $e) {
            return false;
        }
    }
}
