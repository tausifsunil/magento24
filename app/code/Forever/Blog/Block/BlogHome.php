<?php

namespace Forever\Blog\Block;

class BlogHome extends \Magento\Framework\View\Element\Template
{
    /**
     * @var $blogFactory
     */
    protected $blogFactory;

     /**
      * @var $storManager
      */
    protected $storManager;

    /**
     * @param Magento\Framework\View\Element\Template\Context $context
     * @param Forever\Blog\Model\BlogFactory $blogFactory
     * @param Magento\Store\Model\StoreManagerInterface $storManager
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Forever\Blog\Model\BlogFactory $blogFactory,
        \Magento\Store\Model\StoreManagerInterface $storManager,
        array $data = []
    ) {

        parent::__construct($context, $data);
        $this->blogFactory = $blogFactory;
        $this->storManager = $storManager;
    }

    /**
     * Get Collection
     *
     * @return collection
     */
    public function getBlogCollection()
    {
        $now = new \DateTime();
        $collection = $this->blogFactory->create()->getCollection()
            ->addFieldToFilter('publish_time', ['lteq' => $now->format('Y-m-d H:i:s')])->setPageSize(6)
            ->addFieldToFilter('status', 1)
            ->setOrder('publish_time', 'DESC');

        return $collection;
    }

    /**
     * @param $dateTime
     * @return $date
     */
    public function getBlogDate($dateTime)
    {
        $date = strtotime($dateTime);
        return date('d/m/Y', $date);
    }

    /**
     * @param $image
     * @return $blogImage
     */
    public function getBlogImage($image)
    {
        $directory = 'blog/image/';
        $mediapath = $this->storManager->getStore()->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        );
        $blogImage = $mediapath . $directory . $image;
        return $blogImage;
    }

    /**
     * @param $shortContent
     * @return $shortContent
     */
    public function getBlogShortContent($shortContent)
    {
        if (strlen($shortContent) > 110) {
            return substr(strip_tags($shortContent), 0, 110) . '...';
        } else {
            return strip_tags($shortContent);
        }
    }

    /**
     * @param  $viewUrlKey
     * @return $getViewUrl
     */
    public function getViewUrl($viewUrlKey)
    {
        $baseUrl = $this->storManager->getStore()->getBaseUrl();
        $getViewUrl = $baseUrl . 'blog/index/view/' . $viewUrlKey;
        return $getViewUrl;
    }
}
