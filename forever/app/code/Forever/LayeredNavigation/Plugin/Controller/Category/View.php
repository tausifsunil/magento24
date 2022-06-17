<?php

namespace Forever\LayeredNavigation\Plugin\Controller\Category;

use Forever\LayeredNavigation\Helper\Data as LayerData;

class View
{
    const CONFIG_ENABLE = 'layered_navigation/general/ajax_enable';
    /**
     * @var LayerData
     */
    protected $_moduleHelper;
    
    /**
     * @var \Forever\LayeredNavigation\ViewModel\AjaxLayerViewModel $moduleViewmodel
     */
    protected $moduleViewmodel;

    /**
     * View constructor.
     *
     * @param \Forever\LayeredNavigation\ViewModel\AjaxLayerViewModel $moduleViewmodel
     */
    public function __construct(
        \Forever\LayeredNavigation\ViewModel\AjaxLayerViewModel $moduleViewmodel
    ) {
        $this->moduleViewmodel = $moduleViewmodel;
    }

    /**
     * @param \Magento\Catalog\Controller\Category\View $action
     * @param $page
     *
     * @return mixed
     */
    public function afterExecute(\Magento\Catalog\Controller\Category\View $action, $page)
    {
        if ($this->moduleViewmodel->getScopeconfig('layered_navigation/general/ajax_enable')
            && $action->getRequest()->isAjax() && !$action->getRequest()->getParam('ajaxscroll')) {
            $navigation = $page->getLayout()->getBlock('catalog.leftnav');
            $products = $page->getLayout()->getBlock('category.products');
            $result = ['products' => $products->toHtml(), 'navigation' => $navigation->toHtml()];
            $action->getResponse()->representJson(LayerData::jsonEncode($result));
        } else {
            return $page;
        }
    }
}
