<?php

declare(strict_types=1);

namespace Regina\AttributeAtCheckout\Plugin;

use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Regina\AttributeAtCheckout\Constants;

/**
 * Class AddCustomerClassToOrderApi
 */
class AddCustomerClassToOrderApi
{
    /** @var OrderExtensionFactory  */
    private OrderExtensionFactory $orderExtensionFactory;

    /**
     * AddCustomerClassToOrderApi constructor.
     * @param OrderExtensionFactory $orderExtensionFactory
     */
    public function __construct(
        OrderExtensionFactory $orderExtensionFactory
    ) {
        $this->orderExtensionFactory = $orderExtensionFactory;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $order
     * @return OrderInterface
     */
    public function afterGet(OrderRepositoryInterface $subject, OrderInterface $order)
    {
        $this->addAttributesToOrderApi($order);
        return $order;
    }


    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderSearchResultInterface $searchResult
     * @return OrderSearchResultInterface
     */
    public function afterGetList(OrderRepositoryInterface $subject, OrderSearchResultInterface $searchResult)
    {
        foreach ($searchResult as $order) {
            $this->addAttributesToOrderApi($order);
        }
        return $searchResult;
    }

    /**
     * @param OrderInterface $order
     */
    private function addAttributesToOrderApi(OrderInterface $order)
    {
        $extensionAttributes = $order->getExtensionAttributes();
        if ($extensionAttributes === null) {
            $extensionAttributes = $this->getOrderExtensionDependency();
        }

        $extensionAttributes->setData(
            Constants::CUSTOMER_CLASS,
            $this->addValue($order->getData(Constants::CUSTOMER_CLASS))
        );
        $order->setExtensionAttributes($extensionAttributes);
    }

    /**
     * @return \Magento\Sales\Api\Data\OrderExtension
     */
    private function getOrderExtensionDependency()
    {
        return $this->orderExtensionFactory->create();
    }

    /**
     * @param $value
     * @param int $defultValue
     * @return int|mixed
     */
    public function addValue($value, $defultValue = 0)
    {
        if ($value > 0 or !empty($value)) {
            return $value;
        } else {
            return $defultValue;
        }
    }
}
