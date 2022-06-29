<?php

declare(strict_types=1);

namespace Regina\AttributeAtCheckout\Observer;

use Magento\Framework\DataObject\Copy;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\Quote;
use Magento\Sales\Model\Order;
use Regina\AttributeAtCheckout\Constants;
use Regina\AttributeAtCheckout\Service\SaveToCustomerEntity;

/**
 * Class SaveToOrder
 */
class SaveToOrder implements ObserverInterface
{
    /** @var Copy  */
    protected Copy $objectCopyService;

    /** @var SaveToCustomerEntity  */
    private SaveToCustomerEntity $saveToCustomerEntityProxy;

    /**
     * SaveToOrder constructor.
     * @param Copy $objectCopyService
     * @param SaveToCustomerEntity $saveToCustomerEntityProxy
     */
    public function __construct(
        Copy $objectCopyService,
        SaveToCustomerEntity $saveToCustomerEntityProxy
    ) {
        $this->objectCopyService = $objectCopyService;
        $this->saveToCustomerEntityProxy = $saveToCustomerEntityProxy;
    }


    /**
     * @param Observer $observer
     * @return $this|void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(Observer $observer)
    {
        /* @var Order $order */
        $order = $observer->getEvent()->getData('order');
        /* @var Quote $quote */
        $quote = $observer->getEvent()->getData('quote');

        if (!$quote->getCustomerIsGuest()) {
            $this->setCustomerClass($quote);
        }

        $order->setData(Constants::CUSTOMER_CLASS, $quote->getData(Constants::CUSTOMER_CLASS));
        $this->objectCopyService->copyFieldsetToTarget('sales_convert_quote', 'to_order', $quote, $order);

        return $this;
    }


    /**
     * @param Quote $quote
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\State\InputMismatchException
     */
    protected function setCustomerClass(Quote $quote): void
    {
        /** @var \Magento\Customer\Api\Data\CustomerInterface|\Magento\Framework\Api\ExtensibleDataInterface $customer */
        $customer = $quote->getCustomer();
        if ($customerClass = $customer->getCustomAttribute(Constants::CUSTOMER_CLASS)) {
            if ($customerClass->getValue() !== null) {
                $customerClassValue = $customerClass->getValue();
                $customerClassLabel = $this->saveToCustomerEntityProxy->getCustomerClassLabel($customerClassValue);
                $quote->setData(Constants::CUSTOMER_CLASS, $customerClassLabel);
            }
        } else {
            $this->saveToCustomerEntityProxy->saveClassToCustomer($customer, $quote);
        }
    }
}
