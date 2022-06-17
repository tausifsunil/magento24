<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Forever\StoreLocator\Block;

use Magento\Framework\View\Element\Template;

/**
 * @Class StoreList
 */
class StoreList extends Template
{
    /**
     * @var $collectionFactory
     */
    protected $collectionFactory;
    /**
     * @var $scopeConfig
     */
    protected $scopeConfig;
    /**
     * @param Template\Context                                         $context
     * @param \Forever\StoreLocator\Model\ResourceModel\Store\CollectionFactory $collectionFactory
     * @param array                                                    $data
     */
    public function __construct(
        Template\Context $context,
        \Forever\StoreLocator\Model\ResourceModel\Store\CollectionFactory $collectionFactory,
        array $data = []
    ) {

        parent::__construct($context, $data);
        $this->collectionFactory = $collectionFactory;
    }
    /**
     * @return getCollection
     */
    public function getCollection()
    {
        $collection = $this->collectionFactory->create()
        ->addFieldToSelect('*')
        ->addFieldToFilter('status', ['eq' => '1']);
        return $collection;
    }
}
