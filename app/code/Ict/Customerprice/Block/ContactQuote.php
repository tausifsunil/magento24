<?php

/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_Customerprice
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */

namespace Ict\Customerprice\Block;

use Magento\Store\Model\ScopeInterface;

class ContactQuote extends \Magento\Framework\View\Element\Template
{
    public const MODULE_ENABLE = 'ict_customerprice/general/enable';
    public const LOGIN_BUTTON = 'ict_customerprice/general/buttontext';
    public const LOGIN_BUTTON_ENABLE = 'ict_customerprice/general/enable';
    public const CONTACT_BUTTON = 'ict_customerprice/general/contactquote';
    public const CONTACT_BUTTON_ENABLE = 'ict_customerprice/general/enablecontactquote';

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $session;

    /**
     * \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $config;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $session
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\SessionFactory $session,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->session = $session;
        $this->config = $config;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * @return string
     */
    public function getStoreBaseUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl();
    }

    /**
     * @return bool
     */
    public function getCustomerLogin()
    {
        $isLoggedIn = $this->session->create()->isLoggedIn();
        return $isLoggedIn;
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

    /**
     * @return bool
     */
    public function isEnable()
    {
        return $this->getConfigData(self::MODULE_ENABLE);
    }

    /**
     * @return string
     */
    public function getLoginButtonText()
    {
        return $this->getConfigData(self::LOGIN_BUTTON);
    }

    /**
     * @return string
     */
    public function getLoginButtonEnable()
    {
        return $this->getConfigData(self::LOGIN_BUTTON_ENABLE);
    }

    /**
     * @return string
     */
    public function getContactButtonText()
    {
        return $this->getConfigData(self::CONTACT_BUTTON);
    }

    /**
     * @return string
     */
    public function getContactButtonEnable()
    {
        return $this->getConfigData(self::CONTACT_BUTTON_ENABLE);
    }
}
