<?php

namespace Forever\Blog\Block\Sidebar;

use Magento\Framework\View\Element\Template;
use Forever\Blog\Model\ResourceModel\Blog\CollectionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use Forever\Blog\Model\BlogFactory;

/**
 * Class MostView
 * package forever\Blog\Block\Sidebar
 */
class MostView extends Template
{
    const BLOG_RECENT_POST = 'blog/sidebar/number_recent_posts';

    /**
     * @return scopeConfig
     */
    protected $scopeConfig;

    /**
     * @return blogFactory
     */
    protected $blogFactory;

    /**
     * @return storManager
     */
    protected $storManager;

    /**
     *
     * @param Template\Context      $context
     * @param CollectionFactory     $collectionFactory
     * @param BlogFactory           $blogFactory
     * @param ScopeConfigInterface  $scopeConfig
     * @param StoreManagerInterface $storManager
     * @param array                 $data
     */
    public function __construct(
        Template\Context $context,
        CollectionFactory $collectionFactory,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storManager,
        BlogFactory $blogFactory,
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->scopeConfig = $scopeConfig;
        $this->_isScopePrivate = true;
        $this->storManager = $storManager;
        $this->blogFactory = $blogFactory;

        parent::__construct($context, $data);
    }

    /**
     * @return getConfig value
     */
    public function getConfigData($path)
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue($path, $storeScope);
    }

    /**
     * @return Blog Collection
     */
    public function blogCollection()
    {
        $blogCollection = $this->collectionFactory->create();
        return $blogCollection;
    }

    /**
     * @return Recent added Collection
     */
    public function getRecentPost()
    {
        $collection = $this->blogCollection()->setOrder('publish_time', 'DESC');
         
        $collection->getSelect()->limit((int)$this->getConfigData(self::BLOG_RECENT_POST) ?: 4);
        return $collection;
    }

    /**
     * @return MediaUrl
     */
    public function getMediaUrl()
    {
        $media_dir = $this->storManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $media_dir;
    }

    /**
     * @return Blog View URL
     */
    public function getViewUrl($viewUrlKey)
    {
        $baseUrl = $this->storManager->getStore()->getBaseUrl();
        $getViewUrl = $baseUrl . 'blog/index/view/' . $viewUrlKey;
        return $getViewUrl;
    }
}
