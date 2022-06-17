<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Forever\Blog\ViewModel;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;

class BlogData implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $scopeConfig;

    protected $storeScope;
    /**
     * @param \Magento\Catalog\Model\CustomerFactory     $customerFactory
     */
    public const XML_PATH_EMAIL_RECIPIENT = 'blog/general/enable';
    /**
     * @param ScopeConfigInterface  $scopeConfig
     * @param StoreManagerInterface $storeScope
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeScope
    ) {
        $this->storeScope = $storeScope;
        $this->scopeConfig = $scopeConfig;
        $this->_isScopePrivate = true;
    }
    /**
     * @return getConfig
     */
    public function getConfig()
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue(self::XML_PATH_EMAIL_RECIPIENT, $storeScope);
    }
    /**
     * @return getMediaUrl
     */
    public function getMediaUrl()
    {
        $media_dir = $this->storeScope->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $media_dir;
    }
}
