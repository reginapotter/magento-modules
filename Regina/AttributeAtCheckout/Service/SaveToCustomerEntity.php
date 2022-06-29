<?php

declare(strict_types=1);

namespace Regina\AttributeAtCheckout\Service;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Model\Quote;
use Regina\AttributeAtCheckout\Constants;

class SaveToCustomerEntity
{
    private EavConfig $eavConfig;
    private CustomerRepositoryInterface $customerRepositoryProxy;

    public function __construct(
        EavConfig $eavConfig,
        CustomerRepositoryInterface $customerRepositoryProxy
    ) {
        $this->eavConfig = $eavConfig;
        $this->customerRepositoryProxy = $customerRepositoryProxy;
    }


    /**
     * @return \Magento\Eav\Model\Entity\Attribute\AbstractAttribute|null
     * @throws LocalizedException
     */
    protected function getCustomerClassAttribute()
    {
        return $this->eavConfig->getAttribute('customer', Constants::CUSTOMER_CLASS);
    }


    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCustomerClassLabel($value)
    {
        return $this->getCustomerClassAttribute() ? $this->getCustomerClassAttribute()->getSource()->getOptionText($value) : '';
    }


    //get the corresponding value from the label
    protected function getOptionValue($options, $label)
    {
        if (count($options) > 0) {
            foreach ($options as $option) {
                if (isset($option['label']) && $option['label'] == $label) {
                    return $option['value'] ?? $option['label'];
                }
            }
        }

        if (is_string($options[$label]) && isset($options[$label])) {
            return $options[$label];
        }
        return false;
    }


    /**
     * @param \Magento\Customer\Api\Data\CustomerInterface|\Magento\Framework\Api\ExtensibleDataInterface $customer
     * @param Quote $quote
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\State\InputMismatchException
     */
    public function saveClassToCustomer($customer, Quote $quote): void
    {
        if ($customer->getExtensionAttributes()->getCustomerClass() == null) {
            if ($customerClassAttribute = $this->getCustomerClassAttribute()) {
                $options = $customerClassAttribute->getSource()->getAllOptions();
                $value = $this->getOptionValue($options, $quote->getData(Constants::CUSTOMER_CLASS));

                $extensionAttributes = $customer->getExtensionAttributes();
                $extensionAttributes->setCustomerClass($value);

                $customer->setExtensionAttributes($extensionAttributes);
                $customer->setCustomAttribute(Constants::CUSTOMER_CLASS, $value);

                $this->customerRepositoryProxy->save($customer);
            }
        }
    }
}
