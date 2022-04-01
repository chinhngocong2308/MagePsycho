<?php

namespace MagePsycho\RegionCityPro\Plugin\Model\Customer\Delegation;

use MagePsycho\RegionCityPro\Helper\Data as RegionCityProHelper;
use Magento\Customer\Model\Delegation\Data\NewOperation;
use Magento\Customer\Model\Delegation\Storage;
use Magento\Framework\Api\AbstractSimpleObject;
use Magento\Framework\Api\CustomAttributesDataInterface;
use MagePsycho\RegionCityPro\Api\Data\CityInterface;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class InjectCityIdPlugin
{
    /**
     * @var RegionCityProHelper
     */
    protected $regionCityProHelper;

    public function __construct(
        RegionCityProHelper $regionCityProHelper
    ) {
        $this->regionCityProHelper = $regionCityProHelper;
    }

    /**
     * Put city_id data in correct place after object restore.
     *
     * @param Storage $subject
     * @param NewOperation|null $result
     * @return mixed
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterConsumeNewOperation(Storage $subject, $result)
    {
        if (!$result instanceof NewOperation) {
            return $result;
        }

        $customer = $result->getCustomer();

        $this->normalizeDataObjects($customer);
        foreach ($customer->getAddresses() as $address) {
            $this->normalizeDataObjects($address);
        }

        return $result;
    }

    /**
     * If city_id data present then they should be placed in custom attributes
     *
     * @param mixed $dto
     */
    private function normalizeDataObjects($dto)
    {
        if (!$dto instanceof AbstractSimpleObject || !$dto instanceof CustomAttributesDataInterface) {
            return;
        }

        $data = $dto->__toArray();
        if (array_key_exists(CityInterface::ID, $data)) {
            $dto->setCustomAttribute(CityInterface::ID, $data[CityInterface::ID]);
        }

        if (array_key_exists('sub_district_id', $data)) {
            $dto->setCustomAttribute('sub_district_id', $data['sub_district_id']);
        }
    }
}
