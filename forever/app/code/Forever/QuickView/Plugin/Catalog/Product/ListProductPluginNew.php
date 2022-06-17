<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Forever\QuickView\Plugin\Catalog\Product;

use Magento\Framework\Registry;
use Forever\QuickView\Model\Renderer;

/**
 * Class ListProductPlugin append html to list
 */
class ListProductPluginNew
{
    const XML_PATH_QUICKVIEW_BUTTONSTYLE = 'quickview/display/button_style';
    const XML_PATH_ENABLE_QUICKVIEW = 'quickview/display/enable_product_listing';
    /** @var Registry */
    private $registry;

    /** @var Renderer */
    private $renderer;

    /**
     * ListProduct constructor.
     * @param Registry $registry
     * @param Renderer $renderer
     */

    protected $urlInterface;

    protected $scopeConfig;

    public function __construct(
        \Magento\Framework\UrlInterface $urlInterface,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        Registry $registry,
        Renderer $renderer
    ) {
        $this->registry = $registry;
        $this->renderer = $renderer;
        $this->urlInterface = $urlInterface;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param  $subject
     * @param $result
     * @return string
     * @noinspection PhpUndefinedMethodInspection
     */
    public function aroundGetProductDetailsHtml(\Magento\Catalog\Block\Product\ListProduct $subject,
        \Closure $proceed,
        \Magento\Catalog\Model\Product $product   
    ) {
        $result = $proceed($product);
        $isEnable = $this->scopeConfig->getValue(self::XML_PATH_ENABLE_QUICKVIEW,  \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if(!$isEnable){
            return $result;
        }
        $buttonStyle = 'quickview forever-quickview forever-quickview-text' . $this->scopeConfig->getValue(self::XML_PATH_QUICKVIEW_BUTTONSTYLE,  \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $productUrl = $this->urlInterface->getUrl('quickview/catalog_product/view', array('id' => $product->getId()));
        $forever = 'quickview-item-default';
        $initializeBlock = $subject->getLayout()->getBlock('forever.quickview.initialize');
        $initializeHtml='';
        if ($initializeBlock) {
            $initializeHtml = $initializeBlock->toHtml();
        }
        
        return $result . '<a style="display: none" class="'.$buttonStyle.'" data-quickview-url="' . $productUrl . '" data-forever-js="' . $forever . '" href="' . $productUrl .'"><span>' . __("Quickview") . '</span></a>'.$initializeHtml;
    }
}
