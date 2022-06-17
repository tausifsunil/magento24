<?php
/**
 * @author Ict Team
 * @copyright Copyright (c) 2017 Ict (http://icreativetechnologies.com/)
 * @package Ict_Shopbybrand
 */

namespace Ict\Shopbybrand\Model\Maker;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

class Rss
{
 
    protected $scopeConfig;
 
    protected $urlBuilder;
 
    protected $storeManager;

    public function __construct(
        UrlInterface $urlBuilder,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    )
    {
        $this->urlBuilder = $urlBuilder;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * @return bool
     */
    public function isRssEnabled()
    {
        return
            $this->scopeConfig->getValue('rss/config/active', ScopeInterface::SCOPE_STORE) &&
            $this->scopeConfig->getValue('ict_shopbybrand/maker/rss', ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getRssLink()
    {
        return $this->urlBuilder->getUrl(
            'ict_shopbybrand/maker/rss',
            ['store' => $this->storeManager->getStore()->getId()]
        );
    }
}
