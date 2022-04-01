<?php

namespace MagePsycho\RegionCityPro\Setup;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Eav\Api\AttributeRepositoryInterface;
use MagePsycho\RegionCityPro\Model\ResourceModel\Address\Attribute\Backend\City as CityBackend;
use MagePsycho\RegionCityPro\Model\ResourceModel\Address\Attribute\Source\City as CitySource;
use Magento\Customer\Api\AddressMetadataInterface;
use MagePsycho\RegionCityPro\Api\Data\CityInterface;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class InstallData implements InstallDataInterface
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

    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $setup->startSetup();
        $this->addCityIdAddressAttribute($setup);
        $setup->endSetup();
    }

    private function addCityIdAddressAttribute($setup)
    {
        if ($this->checkIfAddressAttributeExists(CityInterface::ID)) {
            return;
        }

        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $customerSetup->addAttribute(
            AddressMetadataInterface::ENTITY_TYPE_ADDRESS,
            CityInterface::ID,
            [
                'label'                 => 'City',
                'type'                  => 'int',
                'input'                 => 'select',
                'required'              => false,
                'visible'               => true,
                'system'                => false,
                'user_defined'          => true,
                'is_used_in_grid'       => false,
                'is_visible_in_grid'    => false,
                'is_filterable_in_grid' => false,
                'is_searchable_in_grid' => false,
                'source'                => CitySource::class,
                'backend'               => CityBackend::class,
                'sort_order'            => 100,
                'position'              => 100,
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
                CityInterface::ID
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
