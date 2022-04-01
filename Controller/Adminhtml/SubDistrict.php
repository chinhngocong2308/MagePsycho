<?php

namespace MagePsycho\RegionCityPro\Controller\Adminhtml;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\Registry;
use MagePsycho\RegionCityPro\Model\SubDistrictFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use MagePsycho\RegionCityPro\Model\Config\Source\Locale;
use Magento\Ui\Component\MassAction\Filter;
use MagePsycho\RegionCityPro\Model\ResourceModel\SubDistrict\CollectionFactory as SubDistrictCollectionFactory;

abstract class SubDistrict extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'MagePsycho_RegionCityPro::sub_district';

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
     * @var SubDistrictFactory
     */
    protected $subDistrictFactory;

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var SubDistrictCollectionFactory
     */
    protected $subDistrictCollectionFactory;

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
        SubDistrictFactory $subDistrictFactory,
        Filter $filter,
        SubDistrictCollectionFactory $subDistrictCollectionFactory,
        Locale $locale
    ) {
        $this->registry = $registry;
        $this->dataPersistor = $dataPersistor;
        $this->resultPageFactory = $resultPageFactory;
        $this->forwardFactory = $forwardFactory;
        $this->jsonFactory = $jsonFactory;
        $this->subDistrictFactory = $subDistrictFactory;
        $this->filter = $filter;
        $this->subDistrictCollectionFactory = $subDistrictCollectionFactory;
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
        $resultPage->setActiveMenu('MagePsycho_RegionCityPro::sub_district')
            ->addBreadcrumb(__('Sub District'), __('Sub District'))
            ->addBreadcrumb(__('Sub Districts'), __('Sub Districts'));
        return $resultPage;
    }
}
