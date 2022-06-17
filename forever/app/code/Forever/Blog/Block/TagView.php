<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Forever\Blog\Block;

use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;

class TagView extends Template
{
    /**
     * @var $blogFactory
     */
    protected $blogFactory;
    /**
     * @var $tagFactory
     */
    protected $tagFactory;
     /**
      * @var $storManager
      */
    protected $storManager;
    /**
     * @param Template\Context                $context
     * @param \Forever\Blog\Model\BlogFactory $blogFactory
     * @param \Forever\Blog\Model\TagFactory  $tagFactory
     * @param array                           $data
     */
    public function __construct(
        Template\Context $context,
        \Forever\Blog\Model\BlogFactory $blogFactory,
        \Forever\Blog\Model\TagFactory $tagFactory,
        StoreManagerInterface $storManager,
        array $data = []
    ) {

        parent::__construct($context, $data);
        $this->blogFactory = $blogFactory;
        $this->tagFactory = $tagFactory;
        $this->storManager = $storManager;
    }
    /**
     * @param  $tagviewUrlKey
     * @return $getTagPostList
     */
    public function getTagPostList($tagviewUrlKey)
    {
        $tagId =  $this->getTagIdByTitle($tagviewUrlKey);
        $blogViewColl = $this->blogFactory->create()->getCollection();
        foreach ($blogViewColl as $key => $val) {
            $tagArr = explode(", ", $val->getTags());
            if (!in_array($tagId, $tagArr)) {
                $blogViewColl->removeItemByKey($val->getId());
            }
        }
        return  $blogViewColl;
    }
    /**
     * @param  $tagviewUrlKey
     * @return $getTagIdByTitle
     */
    public function getTagIdByTitle($tagviewUrlKey)
    {
        $getTagColl = $this->tagFactory->create()->getCollection();
        foreach ($getTagColl as $key => $value) {
            $title = strtolower($value->getTitle());
            $title = str_replace(' ', '-', $title);
            if ($title == $tagviewUrlKey) {
                return $value->getId();
            }
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
