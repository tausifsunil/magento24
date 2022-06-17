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

class PriceBlock implements \Magento\Framework\Event\ObserverInterface
{
    public const ROUTE_NAME = 'catalogsearch';
    public const ENABLE_MODULE_CONFIG = 'ict_customerprice/general/enable';
    public const RENDER_ELEMENT = 'product.price.render.default';

    /**
     * @var \Magento\Framework\View\Layout
     */
    protected $layout;

    /**
     * @var \Magento\Customer\Model\SessionFactory
     */
    protected $customerSession;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $requestInterface;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $config;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param \Magento\Framework\View\Layout $layout
     * @param \Magento\Customer\Model\SessionFactory $customerSession
     * @param \Magento\Framework\App\RequestInterface $requestInterface
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\View\Layout $layout,
        \Magento\Customer\Model\SessionFactory $customerSession,
        \Magento\Framework\App\RequestInterface $requestInterface,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_layout = $layout;
        $this->customerSession = $customerSession;
        $this->requestInterface = $requestInterface;
        $this->config = $config;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritdoc
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $routeName = $this->requestInterface->getRouteName();
        $customerLogin = $this->customerSession->create()->isLoggedIn();
        $enableCustomerPrice = $this->getConfigData(self::ENABLE_MODULE_CONFIG);

        if ($enableCustomerPrice == 1 && !$customerLogin) {
            $this->_layout->unsetElement(self::RENDER_ELEMENT);
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
