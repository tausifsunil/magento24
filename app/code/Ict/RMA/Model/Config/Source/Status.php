<?php

/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_RMA
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */

namespace Ict\RMA\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Status implements ArrayInterface
{
    public const RMA_STATUS_ID = 'rma_status_id';
    public const RMA_STATUSES = 'statuses';

    /**
     * @var \Ict\RMA\Model\RmaStatusFactory
     */
    protected $collection;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @param Ict\RMA\Model\RmaStatusFactory $collection
     */
    public function __construct(
        \Ict\RMA\Model\RmaStatusFactory $collection
    ) {
        $this->collection = $collection;
    }

    /**
     * value of array
     *
     * @return array
     */
    public function toOptionArray()
    {
        $items = [];
        if (!$this->options) {
            $this->options = $this->collection->create()->getCollection()->getData();
            foreach ($this->options as $key => $value) {
                $items[$key]['value'] = $value[self::RMA_STATUS_ID];
                $items[$key]['label'] = $value[self::RMA_STATUSES];
            }
        }
        return $items;
    }
}
