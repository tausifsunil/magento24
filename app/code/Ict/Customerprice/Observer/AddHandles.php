<?php

/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_Customerprice
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */

namespace Ict\Customerprice\Observer;

use Magento\Store\Model\ScopeInterface;

class AddHandles implements \Magento\Framework\Event\ObserverInterface
{
    public const ENABLE = 'ict_customerprice/general/enable';
    public const LOGIN = 'customer_logged_in';
    public const LOGOUT = 'customer_logged_out';

    /**
     * @var \Magento\Customer\Model\SessionFactory
     */
    protected $customerSession;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $config;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param \Magento\Customer\Model\SessionFactory $customerSession
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Customer\Model\SessionFactory $customerSession,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->customerSession = $customerSession;
        $this->config = $config;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritdoc
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $layout = $observer->getEvent()->getLayout();
        $enableCustomerPrice = $this->getConfigData(self::ENABLE);

        if ($enableCustomerPrice == 1) {
            if ($this->customerSession->create()->isLoggedIn()) {
                $layout->getUpdate()->addHandle(self::LOGIN);
            } else {
                $layout->getUpdate()->addHandle(self::LOGOUT);
            }
        }
    }

    /**
     * @return Scope Config Value | string
     */
    public function getConfigData($path)
    {
        $value = $this->config->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getStoreId()
        );
        return $value;
    }
}
