<?php

declare(strict_types=1);
namespace Regina\AttributeAtCheckout\Service;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Regina\AttributeAtCheckout\Constants;

/**
 * Class IsCustomerClassAssigned
 */
class IsCustomerClassAssigned
{
    /** @var CustomerRepositoryInterface  */
    private CustomerRepositoryInterface $customerRepository;

    /**
     * IsCustomerClassAssigned constructor.
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param $customerId
     * @return bool
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function isCustomerClassAssigned($customerId)
    {
        $customer = $this->customerRepository->getById($customerId);
        if ($customer->getCustomAttribute(Constants::CUSTOMER_CLASS)) {
            return !($customer->getCustomAttribute(Constants::CUSTOMER_CLASS)->getValue() == null);
        }
        return false;
    }

    /**
     * @param $customerId
     * @return mixed|null
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getCustomerClass($customerId)
    {
        $customerClass = null;
        $customer = $this->customerRepository->getById($customerId);
        if ($customer->getCustomAttribute(Constants::CUSTOMER_CLASS)) {
            $customerClass = $customer->getCustomAttribute(Constants::CUSTOMER_CLASS)->getValue();
        }
        return $customerClass;
    }
}
