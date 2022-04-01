<?php

namespace MagePsycho\RegionCityPro\Ui\Component\Form\Locale;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Form\FieldFactory;
use Magento\Ui\Component\Form\Fieldset as BaseFieldset;
use MagePsycho\RegionCityPro\Model\Config\Source\Locale;

class CityFieldset extends BaseFieldset
{
    /**
     * @var FieldFactory
     */
    protected $fieldFactory;

    /**
     * @var Locale
     */
    protected $locale;

    public function __construct(
        ContextInterface $context,
        FieldFactory $fieldFactory,
        Locale $locale,
        $components = [],
        array $data = []
    ) {
        parent::__construct($context, $components, $data);
        $this->fieldFactory = $fieldFactory;
        $this->locale = $locale;
    }

    public function getChildComponents()
    {
        $locales = $this->locale->toOptionArray();
        foreach ($locales as $locale) {
            $fieldInstance = $this->fieldFactory->create();
            $fieldName = 'city_locales[' . $locale["value"] . ']';
            $fieldLabel = $locale["label"];
            $dataScope = 'city_locales.' . $locale["value"];

            $fieldInstance->setData(
                [
                    'config' =>   [
                        'label' => $fieldLabel,
                        'formElement' => 'input',
                        'source' => $dataScope,
                        'dataScope' => $dataScope,
                        'tooltip' => [
                            'description' => __('Only fill up if you want to override the Default value')
                        ]
                    ],
                    'name' => $fieldName
                ]
            );
            $fieldInstance->prepare();
            $this->addComponent($fieldName, $fieldInstance);
        }

        return parent::getChildComponents();
    }
}
