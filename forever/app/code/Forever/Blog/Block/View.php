<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Forever\Blog\Block;

use Magento\Framework\View\Element\Template;

class View extends Template
{
    /**
     * @var $blogFactory
     */
    protected $blogFactory;
    /**
     * @param Template\Context                $context
     * @param \Forever\Blog\Model\BlogFactory $blogFactory
     * @param array                           $data
     */
    public function __construct(
        Template\Context $context,
        \Forever\Blog\Model\BlogFactory $blogFactory,
        array $data = []
    ) {

        parent::__construct($context, $data);
        $this->blogFactory = $blogFactory;
    }
    /**
     * @param  $urlKey
     * @return $getPost
     */
    public function getPost($urlKey)
    {
        $collection = $this->blogFactory->create()->load($urlKey, 'url_key');
        return $collection;
    }
}
