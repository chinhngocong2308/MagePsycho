<?php

namespace MagePsycho\RegionCityPro\Controller\Adminhtml\Region;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use MagePsycho\RegionCityPro\Model\Export\Region\ConvertToCsv;
use Magento\Framework\App\Response\Http\FileFactory;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ExportToCsv extends Action
{
    /**
     * @var ConvertToCsv
     */
    protected $converter;

    /**
     * @var FileFactory
     */
    protected $fileFactory;

    public function __construct(
        Context $context,
        ConvertToCsv $converter,
        FileFactory $fileFactory
    ) {
        parent::__construct($context);
        $this->converter = $converter;
        $this->fileFactory = $fileFactory;
    }

    /**
     * Export data provider to CSV
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function execute()
    {
        return $this->fileFactory->create(
            'directory_regions_export.csv',
            $this->converter->getCsvFile(),
            'var'
        );
    }
}
