<?php

namespace MagePsycho\RegionCityPro\Plugin\Model\Sales\AdminOrder;

use Magento\Framework\Exception\LocalizedException;

/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class CreatePlugin
{
    /**
     * @var \MagePsycho\RegionCityPro\Helper\Data
     */
    private $regionCityProHelper;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    public function __construct(
        \MagePsycho\RegionCityPro\Helper\Data $regionCityProHelper,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->regionCityProHelper = $regionCityProHelper;
        $this->request = $request;
    }

    /**
     * Save City id to City Quote address table.
     *
     * @param \Magento\Sales\Model\AdminOrder\Create $subject
     * @param $result
     * @return mixed
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function afterSaveQuote(
        \Magento\Sales\Model\AdminOrder\Create $subject,
        $result
    ) {
        $this->regionCityProHelper->log(__METHOD__, true);
        if ($subject->getQuote()->getId()) {
            $post = $this->request->getPost('order');
            $quote = $subject->getQuote();
            $shippingAddress = $quote->getShippingAddress();
            $billingAddress = $quote->getBillingAddress();

            if ($billingAddress && $billingAddress->getId() && isset($post['billing_address'])) {
                if (isset($post['billing_address']['city_id'])) {
                    $billingAddress->setCityId($post['billing_address']['city_id']);
                }
            }

            if (! $quote->isVirtual() && $shippingAddress && $shippingAddress->getId()) {
                if (isset($post['billing_address'])
                    && isset($post['billing_address']['city_id'])
                    && ($shippingAddress->getSameAsBilling() || $shippingAddress->getSameAsBilling() == 1)
                ) {
                    $shippingAddress->setCityId($post['billing_address']['city_id']);
                } elseif (isset($post['shipping_address'])
                    && isset($post['shipping_address']['city_id'])
                ) {
                    $shippingAddress->setCityId($post['shipping_address']['city_id']);
                }
            }
        }
        return $result;
    }
}
