<?php

declare(strict_types=1);

namespace Regina\AttributeAtCheckout\Plugin;

use Magento\Quote\Model\QuoteRepository;
use Regina\AttributeAtCheckout\Constants;

/**
 * Class SaveToQuote
 */
class SaveToQuote
{
    /** @var QuoteRepository  */
    protected QuoteRepository $quoteRepository;

    /**
     * SaveToQuote constructor.
     * @param QuoteRepository $quoteRepository
     */
    public function __construct(
        QuoteRepository $quoteRepository
    ) {
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
     * @param $cartId
     * @param \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    ) {
        if (!$extensionAttributes = $addressInformation->getExtensionAttributes()) {
            return;
        }
        if (!$customerClass = $extensionAttributes->getCustomerClass()) {
            return;
        }
        $quote = $this->quoteRepository->getActive($cartId);

        $quote->setData(Constants::CUSTOMER_CLASS, $customerClass);
    }
}
