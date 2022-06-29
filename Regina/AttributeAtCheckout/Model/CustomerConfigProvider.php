<?php

declare(strict_types=1);
namespace Regina\AttributeAtCheckout\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Eav\Model\Config;
use Regina\AttributeAtCheckout\Constants;

/**
 * Class CustomerConfigProvider
 */
class CustomerConfigProvider implements ConfigProviderInterface
{
    /** @var Config  */
    private Config $eavConfig;

    /**
     * CustomerConfigProvider constructor.
     * @param Config $eavConfig
     */
    public function __construct(
        Config $eavConfig
    ) {
        $this->eavConfig = $eavConfig;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getConfig()
    {
        $customerClass = $this->eavConfig->getAttribute('customer', Constants::CUSTOMER_CLASS);
        $config = [];
        $config['customer_class'] = $customerClass->getSource()->getAllOptions();
        return $config;
    }
}
