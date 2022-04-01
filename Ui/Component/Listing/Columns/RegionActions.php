<?php

namespace MagePsycho\RegionCityPro\Ui\Component\Listing\Columns;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class RegionActions extends Column
{
    const EDIT_PAGE_ROUTE   = 'regioncitypro/region/edit';
    const DELETE_PAGE_ROUTE = 'regioncitypro/region/delete';

    /**
     * @var UrlInterface
     */
    protected $urlInterface;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlInterface,
        array $components = [],
        array $data = []
    ) {
        $this->urlInterface = $urlInterface;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $name = $this->getData('name');
                if (isset($item['region_id'])) {
                    $item[$name]['edit'] = [
                        'href'  => $this->urlInterface->getUrl(
                            self::EDIT_PAGE_ROUTE,
                            ['region_id' => $item['region_id']]
                        ),
                        'label' => __('Edit')
                    ];

                    $item[$name]['delete'] = [
                        'href'      => $this->urlInterface->getUrl(
                            self::DELETE_PAGE_ROUTE,
                            ['region_id' => $item['region_id']]
                        ),
                        'label'     => __('Delete'),
                        'confirm'   => [
                            'title'     => __('Delete '),
                            'message'   => __('Are you sure you want to delete a record?')
                        ]
                    ];
                }
            }
        }
        return $dataSource;
    }
}
