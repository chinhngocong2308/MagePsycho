<?php

namespace MagePsycho\RegionCityPro\Controller\Adminhtml;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\Registry;
use MagePsycho\RegionCityPro\Model\RegionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use MagePsycho\RegionCityPro\Model\Config\Source\Locale;
use Magento\Ui\Component\MassAction\Filter;
use MagePsycho\RegionCityPro\Model\ResourceModel\Region\CollectionFactory as RegionCollectionFactory;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
abstract class Region extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'MagePsycho_RegionCityPro::region';

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
     * @var RegionFactory
     */
    protected $regionFactory;

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var RegionCollectionFactory
     */
    protected $regionCollectionFactory;

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
        RegionFactory $regionFactory,
        Filter $filter,
        RegionCollectionFactory $regionCollectionFactory,
        Locale $locale
    ) {
        $this->registry = $registry;
        $this->dataPersistor = $dataPersistor;
        $this->resultPageFactory = $resultPageFactory;
        $this->forwardFactory = $forwardFactory;
        $this->jsonFactory = $jsonFactory;
        $this->regionFactory = $regionFactory;
        $this->filter = $filter;
        $this->regionCollectionFactory = $regionCollectionFactory;
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
        $resultPage->setActiveMenu('MagePsycho_RegionCityPro::region')
            ->addBreadcrumb(__('Region'), __('Region'))
            ->addBreadcrumb(__('Regions'), __('Regions'));
        return $resultPage;
    }
}
