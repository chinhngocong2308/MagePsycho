<?php

namespace MagePsycho\RegionCityPro\Controller\Adminhtml;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\Registry;
use MagePsycho\RegionCityPro\Model\CityFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use MagePsycho\RegionCityPro\Model\Config\Source\Locale;
use Magento\Ui\Component\MassAction\Filter;
use MagePsycho\RegionCityPro\Model\ResourceModel\City\CollectionFactory as CityCollectionFactory;

abstract class City extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'MagePsycho_RegionCityPro::city';

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $registry;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var ForwardFactory
     */
    protected $forwardFactory;

    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * @var CityFactory
     */
    protected $cityFactory;

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CityCollectionFactory
     */
    protected $cityCollectionFactory;

    /**
     * @var Locale
     */
    protected $locale;

    public function __construct(
        Context $context,
        Registry $registry,
        DataPersistorInterface $dataPersistor,
        PageFactory $resultPageFactory,
        ForwardFactory $forwardFactory,
        JsonFactory $jsonFactory,
        CityFactory $cityFactory,
        Filter $filter,
        CityCollectionFactory $cityCollectionFactory,
        Locale $locale
    ) {
        $this->registry = $registry;
        $this->dataPersistor = $dataPersistor;
        $this->resultPageFactory = $resultPageFactory;
        $this->forwardFactory = $forwardFactory;
        $this->jsonFactory = $jsonFactory;
        $this->cityFactory = $cityFactory;
        $this->filter = $filter;
        $this->cityCollectionFactory = $cityCollectionFactory;
        $this->locale = $locale;
        parent::__construct($context);
    }

    /**
     * Init page
     *
     * @param \Magento\Backend\Model\View\Result\Page $resultPage
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function initPage($resultPage)
    {
        $resultPage->setActiveMenu('MagePsycho_RegionCityPro::city')
            ->addBreadcrumb(__('City'), __('City'))
            ->addBreadcrumb(__('Cities'), __('Cities'));
        return $resultPage;
    }
}
