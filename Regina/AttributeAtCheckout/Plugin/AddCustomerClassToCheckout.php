<?php

declare(strict_types=1);
namespace Regina\AttributeAtCheckout\Plugin;

use Magento\Checkout\Block\Checkout\LayoutProcessor;
use Magento\Customer\Model\Session;
use Regina\AttributeAtCheckout\Constants;
use Regina\AttributeAtCheckout\Service\IsCustomerClassAssigned;

/**
 * Class AddCustomerClassToCheckout
 */
class AddCustomerClassToCheckout
{
    /** @var Session  */
    protected Session $customerSession;

    /** @var IsCustomerClassAssigned  */
    private IsCustomerClassAssigned $customerClassAssigned;

    /**
     * AddCustomerClassToCheckout constructor.
     * @param Session $customerSession
     * @param IsCustomerClassAssigned $customerClassAssigned
     */
    public function __construct(
        Session $customerSession,
        IsCustomerClassAssigned $customerClassAssigned
    ) {
        $this->customerSession = $customerSession;
        $this->customerClassAssigned = $customerClassAssigned;
    }

    /**
     * @param LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function afterProcess(
        LayoutProcessor $subject,
        array  $jsLayout
    ) {
        $customerClassField = [
            'component' => 'Regina_AttributeAtCheckout/js/view/form/element/customer-class',
            'config' => [
                'customScope' => 'shippingAddress.custom_attributes'
            ],
            'dataScope' => 'shippingAddress.custom_attributes.customer_class',
            'provider' => 'checkoutProvider',
            'displayArea' => 'before-form',
            'visible' => true,
            'id' => 'customer_class',
            'validation' => [
                'required-entry' => 1
            ]
        ];
        if ($this->customerSession->isLoggedIn()) {
            $customerID = $this->customerSession->getId();
            $isCustomerClassAssigned = $this->customerClassAssigned->isCustomerClassAssigned($customerID);
            if (!$isCustomerClassAssigned) {
                $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
                ['shippingAddress']['children']['before-form']['children'][Constants::CUSTOMER_CLASS] = $customerClassField;
            }
        } else {
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['before-form']['children'][Constants::CUSTOMER_CLASS] = $customerClassField;
        }
        return $jsLayout;
    }
}
