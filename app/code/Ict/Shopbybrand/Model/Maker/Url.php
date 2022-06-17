<?php
/**
 * @author Ict Team
 * @copyright Copyright (c) 2017 Ict (http://icreativetechnologies.com/)
 * @package Ict_Shopbybrand
 */

namespace Ict\Shopbybrand\Model\Maker;

use Magento\Framework\UrlInterface;
use Ict\Shopbybrand\Model\Maker;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Url
{
    const LIST_URL_CONFIG_PATH = 'ict_shopbybrand/maker/list_url';
 
    const URL_PREFIX_CONFIG_PATH = 'ict_shopbybrand/maker/url_prefix';
 
    const URL_SUFFIX_CONFIG_PATH = 'ict_shopbybrand/maker/url_suffix';
 
    protected $urlBuilder;
 
    protected $scopeConfig;

    public function __construct(
        UrlInterface $urlBuilder,
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->urlBuilder = $urlBuilder;
        $this->scopeConfig = $scopeConfig;
    }

    public function getListUrl()
    {
        $sefUrl = $this->scopeConfig->getValue(self::LIST_URL_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
        if ($sefUrl) {
            return $this->urlBuilder->getUrl('', ['_direct' => $sefUrl]);
        }
        return $this->urlBuilder->getUrl('ict_shopbybrand/maker/index');
    }

    /**
     * @param Maker $maker
     * @return string
     */
    public function getMakerUrl(Maker $maker)
    {
        if ($urlKey = $maker->getUrlKey()) {
            $prefix = $this->scopeConfig->getValue(
                self::URL_PREFIX_CONFIG_PATH,
                ScopeInterface::SCOPE_STORE
            );
            $suffix = $this->scopeConfig->getValue(
                self::URL_SUFFIX_CONFIG_PATH,
                ScopeInterface::SCOPE_STORE
            );
            $path = (($prefix) ? $prefix . '/' : '').
                $urlKey .
                (($suffix) ? '.'. $suffix : '');
            return $this->urlBuilder->getUrl('', ['_direct'=>$path]);
        }
        return $this->urlBuilder->getUrl('ict_shopbybrand/maker/view', ['id' => $maker->getId()]);
    }
}
