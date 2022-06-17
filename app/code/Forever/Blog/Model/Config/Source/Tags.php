<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Forever\Blog\Model\Config\Source;

class Tags implements \Magento\Framework\Option\ArrayInterface
{
    protected $collectionFactory;

    public function __construct(
        \Forever\Blog\Model\ResourceModel\Tag\CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }
    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array("value" => "<value>", "label"=> "<label>"), ...)
     */
    public function toOptionArray()
    {
         $tagCollection = $this->collectionFactory->create();
            $options = [];
        foreach ($tagCollection as $tag) {
            $options[] = [
                'label' => $tag->getTitle(),
                'value' => $tag->getId()
            ];
        }
            return $options;
    }
}
