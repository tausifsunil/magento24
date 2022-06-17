<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Forever\QuickView\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Config provide configurations
 */
class Config
{
    const XML_PATH_ENABLED_MODULE = 'quickview/general/enabled';
    const XML_PATH_DISPLAY_CONTAINER = 'quickview/display/category';
    const XML_PATH_DISPLAY_ENABLE_PRODUCT_LIST = 'quickview/display/enable_product_listing';
    const XML_PATH_DISPLAY_ENABLE_VIEW_DETAIL = 'quickview/display/enable_view_detail';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /** @var StoreManagerInterface */
    protected $storeManager;

    /**
     * Config constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * Check if module enabled
     *
     * @param null|string|bool|int|Store $store
     * @return bool
     */
    public function isEnabled($store = null)
    {
        return $this->scopeConfig->isSetFlag(
            static::XML_PATH_ENABLED_MODULE,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @param null $store
     * @return mixed
     */
    public function getContainerPath($store = null)
    {
        return $this->scopeConfig->getValue(
            static::XML_PATH_DISPLAY_CONTAINER,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @param null $store
     * @return bool
     */
    public function isEnableProductList($store = null)
    {
        return $this->scopeConfig->isSetFlag(
            static::XML_PATH_DISPLAY_ENABLE_PRODUCT_LIST,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @param null $store
     * @return bool
     */
    public function isEnableViewDetail($store = null)
    {
        return $this->scopeConfig->isSetFlag(
            static::XML_PATH_DISPLAY_ENABLE_VIEW_DETAIL,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }
}
