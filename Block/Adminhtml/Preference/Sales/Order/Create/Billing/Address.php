<?php

namespace MagePsycho\RegionCityPro\Block\Adminhtml\Preference\Sales\Order\Create\Billing;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Address extends \Magento\Sales\Block\Adminhtml\Order\Create\Billing\Address
{
    protected function _addAttributesToForm($attributes, \Magento\Framework\Data\Form\AbstractForm $form)
    {
        // Custom sorting
        $addressOrder = ['country_id' => 0, 'region' => 1, 'region_id' => 2, 'city' => 3, 'city_id' => 4];
        $attributes = $this->customSortAttributes($attributes, $addressOrder);

        // add additional form types
        $types = $this->_getAdditionalFormElementTypes();
        foreach ($types as $type => $className) {
            $form->addType($type, $className);
        }
        $renderers = $this->_getAdditionalFormElementRenderers();
        foreach ($attributes as $attribute) {
            $inputType = $attribute->getFrontendInput();

            if ($inputType) {
                $element = $form->addField(
                    $attribute->getAttributeCode(),
                    $inputType,
                    [
                        'name' => $attribute->getAttributeCode(),
                        'label' => __($attribute->getStoreLabel()),
                        'class' => $this->getValidationClasses($attribute),
                        'required' => $attribute->isRequired(),
                    ]
                );
                if ($inputType == 'multiline') {
                    $element->setLineCount($attribute->getMultilineCount());
                }
                $element->setEntityAttribute($attribute);
                $this->_addAdditionalFormElementData($element);

                if (!empty($renderers[$attribute->getAttributeCode()])) {
                    $element->setRenderer($renderers[$attribute->getAttributeCode()]);
                }

                if ($inputType == 'select' || $inputType == 'multiselect') {
                    $options = [];
                    foreach ($attribute->getOptions() as $optionData) {
                        $data = $this->dataObjectProcessor->buildOutputDataArray(
                            $optionData,
                            \Magento\Customer\Api\Data\OptionInterface::class
                        );
                        foreach ($data as $key => $value) {
                            if (is_array($value)) {
                                unset($data[$key]);
                                $data['value'] = $value;
                            }
                        }
                        $options[] = $data;
                    }
                    $element->setValues($options);
                } elseif ($inputType == 'date') {
                    $format = $this->_localeDate->getDateFormat(
                        \IntlDateFormatter::SHORT
                    );
                    $element->setDateFormat($format);
                }
            }
        }

        return $this;
    }

    public function customSortAttributes($attributes, $order)
    {
        $insertAt = 0;
        foreach ($attributes as $key => $_) {
            if (isset($order[$key])) {
                break;
            }
            $insertAt++;
        }

        $special = [];
        foreach ($order as $key => $_) {
            if (isset($attributes[$key])) {
                $special[$key] = $attributes[$key];
            }
        }

        $result = [];
        foreach ($attributes as $key => $value) {
            if (! isset($order[$key])) {
                $result[$key] = $value;
            } elseif (count($result) == $insertAt) {
                $result = array_merge($result, $special);
            }
        }

        return $result;
    }
}
