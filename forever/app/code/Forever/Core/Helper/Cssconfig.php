<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Forever\Core\Helper;

class Cssconfig extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $storeManager;
    protected $generatedCssFolder;
    protected $generatedCssPath;
    protected $generatedCssDir;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
        
        $base = BP;
        
        $this->generatedCssFolder = 'forever/configed_css/';
        $this->generatedCssPath = 'pub/media/'.$this->generatedCssFolder;
        $this->generatedCssDir = $base.'/'.$this->generatedCssPath;
        
        parent::__construct($context);
    }

    public function getBaseMediaUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

    public function getCssConfigDir()
    {
        return $this->generatedCssDir;
    }

    public function getDesignFile()
    {
        return $this->getBaseMediaUrl(). $this->generatedCssFolder . 'css_' . $this->storeManager->getStore()->getCode() . '.css';
    }

    public function getPortoWebDir()
    {
        return $this->getBaseMediaUrl().'forever/web/';
    }

    public function getDynamicCssLink()
    {
        return '<link rel="stylesheet" type="text/css" media="all" href="'.$this->getDesignFile().'"/>';
    }
}
