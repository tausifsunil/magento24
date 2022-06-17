<?php

namespace Forever\Blog\Block;

use Magento\Framework\View\Element\Template;
use Magento\Theme\Block\Html\Pager;

/**
 * @Class PostList
 */
class PostList extends Template
{
    /**
     * @var \Forever\Blog\Model\ResourceModel\Blog\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storManager;

    /**
     * @param Template\Context $context
     * @param \Forever\Blog\Model\ResourceModel\Blog\CollectionFactory $collectionFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storManager
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Forever\Blog\Model\ResourceModel\Blog\CollectionFactory $collectionFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storManager,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->collectionFactory = $collectionFactory;
        $this->scopeConfig = $scopeConfig;
        $this->storManager = $storManager;
    }

    /**
     * @return getCollection
     */
    public function getCollection()
    {
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : 5;

        $collection = $this->collectionFactory->create()
            ->addFieldToSelect('*')
            ->addFieldToFilter('status', ['eq' => '1']);
        $collection->setPageSize($pageSize);
        $collection->setCurPage($page);

        return $collection;
    }

    /**
     * @return _prepareLayout
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->pageConfig->getTitle()->set(__('My Pagination'));
        $configValue = $this->scopeConfig->getValue(
            'blog/general/list_per_page_values',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        $pageLimit = explode(',', $configValue);
        $setLimit = [];
        foreach ($pageLimit as $key => $value) {
                $setLimit[$value] = $value;
        }
        if ($this->getCollection()) {
            $pager = $this->getLayout()->createBlock(
                Pager::class,
                'custom.history.pager'
            )->setAvailableLimit($setLimit)
            ->setShowPerPage(true)->setCollection(
                $this->getCollection()
            );
            $this->setChild('pager', $pager);
            $this->getCollection()->load();
        }
        return $this;
    }

    /**
     * @return getPagerHtml
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * @return blog view URL
     */
    public function getViewUrl($viewUrlKey)
    {
        $baseUrl = $this->storManager->getStore()->getBaseUrl();
        $getViewUrl = $baseUrl . 'blog/index/view/' . $viewUrlKey;
        return $getViewUrl;
    }
}
