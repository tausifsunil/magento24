<?php

namespace Forever\LayeredNavigation\Plugins\Model\Layer\Filter;

class Item
{
    /**
     * @var \Magento\Framework\UrlInterface
     * */
    protected $_url;

    /**
     * @var \Magento\Theme\Block\Html\Pager
     * */
    protected $_htmlPagerBlock;

    /**
     * @var \Magento\Framework\App\RequestInterface
     * */
    protected $_request;

    /**
     * @var \Forever\LayeredNavigation\Helper\Configdata
     */
    protected $_moduleHelper;

    /**
     * @param Magento\Framework\UrlInterface $url
     * @param Magento\Theme\Block\Html\Pager $htmlPagerBlock
     * @param Magento\Framework\App\RequestInterface $request
     * @param Forever\LayeredNavigation\Helper\Configdata $moduleHelper
     */
    public function __construct(
        \Magento\Framework\UrlInterface $url,
        \Magento\Theme\Block\Html\Pager $htmlPagerBlock,
        \Magento\Framework\App\RequestInterface $request,
        \Forever\LayeredNavigation\Helper\Configdata $moduleHelper
    ) {
        $this->_url = $url;
        $this->_htmlPagerBlock = $htmlPagerBlock;
        $this->_request = $request;
        $this->_moduleHelper = $moduleHelper;
    }

    /**
     * create url and return url
     *
     * @param $item
     * @param $proceed
     *
     * @return mix | string | int
     * */
    public function aroundGetUrl(\Magento\Catalog\Model\Layer\Filter\Item $item, $proceed)
    {
        if (!$this->_moduleHelper->isEnabled()) {
            return $proceed();
        }

        $value = [];
        $requestVar = $item->getFilter()->getRequestVar();
        if ($requestValue = $this->_request->getParam($requestVar)) {
            $value = explode(',', $requestValue);
        }
        $value[] = $item->getValue();

        if ($requestVar == 'price') {
            $value = ["{price_start}-{price_end}"];
        }

        $query = [
            $item->getFilter()->getRequestVar() => implode(',', $value),
            $this->_htmlPagerBlock->getPageVarName() => null,
        ];
        if (isset($query['cat'])) {
            $cat = explode(',', $query['cat']);
            $number = count($cat) - 1;
            $query['cat'] = $cat[$number];
        }
        
        return $this->_url->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true, '_query' => $query]);
    }

    /**
     * @param $item
     * @param $proceed
     *
     * @return mix | string | int
     * */
    public function aroundGetRemoveUrl(\Magento\Catalog\Model\Layer\Filter\Item $item, $proceed)
    {
        if (!$this->_moduleHelper->isEnabled()) {
            return $proceed();
        }

        $value = [];
        $requestVar = $item->getFilter()->getRequestVar();
        if ($requestValue = $this->_request->getParam($requestVar)) {
            $value = explode(',', $requestValue);
        }
        $item_array[] = $item->getValue();
        if (in_array($item->getValue(), $value)) {
            $value = array_diff($value, $item_array);
        }

        if ($requestVar == 'price') {
            $value = [];
        }

        $query = [$requestVar => count($value) ? implode(',', $value) : $item->getFilter()->getResetValue()];
        $params['_current'] = true;
        $params['_use_rewrite'] = true;
        $params['_query'] = $query;
        $params['_escape'] = true;
        return $this->_url->getUrl('*/*/*', $params);
    }
}
