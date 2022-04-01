<?php
namespace MagePsycho\RegionCityPro\Ui\Component\Form\Locale;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Form\Fieldset as BaseFieldset;

class RegionFieldset extends BaseFieldset
{
    /**
     * @var \Magento\Ui\Component\Form\FieldFactory
     */
    protected $fieldFactory;
    /**
     * @var \MagePsycho\RegionCityPro\Model\Config\Source\Locale
     */
    protected $allStoresLocale;

    public function __construct(
        ContextInterface $context,
        \Magento\Ui\Component\Form\FieldFactory $fieldFactory,
        \MagePsycho\RegionCityPro\Model\Config\Source\Locale $allStoresLocale,
        $components = [],
        array $data = []
    ) {
        parent::__construct($context, $components, $data);
        $this->fieldFactory = $fieldFactory;
        $this->allStoresLocale = $allStoresLocale;
    }

    public function getChildComponents()
    {
        $allStoresLocale = $this->allStoresLocale->toOptionArray();

        foreach ($allStoresLocale as $store) {
            $fieldInstance = $this->fieldFactory->create();
            $fieldName = 'region_locales[' . $store["value"] . ']';
            $fieldLabel = $store["label"];
            $dataScope = 'region_locales.' . $store["value"];

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
                    'name' => $fieldName,
                ]
            );
            $fieldInstance->prepare();
            $this->addComponent($fieldName, $fieldInstance);
        }
        return parent::getChildComponents();
    }
}
