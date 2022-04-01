<?php

namespace MagePsycho\RegionCityPro\Model\Import\City;

use Magento\Framework\Validator\AbstractValidator;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Validator extends AbstractValidator implements RowValidatorInterface
{
    /**
     * @var array
     */
    protected $_rowData;

    /**
     * @var \MagePsycho\RegionCityPro\Model\Import\City
     */
    protected $context;

    /**
     * @var RowValidatorInterface[]|AbstractValidator[]
     */
    protected $validators = [];

    /**
     * @param RowValidatorInterface[] $validators
     */
    public function __construct($validators = [])
    {
        $this->validators = $validators;
    }

    /**
     * @inheritdoc
     */
    public function isValid($value)
    {
        $this->_rowData = $value;
        $this->_clearMessages();
        $returnValue = true;
        foreach ($this->validators as $validator) {
            if (!$validator->isValid($value)) {
                $returnValue = false;
                $this->_addMessages($validator->getMessages());
            }
        }
        return $returnValue;
    }

    public function init($context)
    {
        $this->context = $context;
        foreach ($this->validators as $validator) {
            $validator->init($context);
        }
        return $this;
    }
}
