<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Forever\StoreLocator\ViewModel;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;

class ConfigData implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $scopeConfig;

    protected $storeScope;
    /**
     * @param \Magento\Catalog\Model\CustomerFactory     $customerFactory
     */
    // public const MODULE_ENABLE = 'storelocator/general/enable';
    // public const STORE_SCOPE_LAT = 'storelocator/mapscope/lat';
    // public const STORE_SCOPE_LNG = 'storelocator/mapscope/lng';
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
    public function getConfig($scopeValue)
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue($scopeValue, $storeScope);
    }
}
