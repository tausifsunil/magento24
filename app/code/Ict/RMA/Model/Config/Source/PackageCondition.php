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

class PackageCondition implements ArrayInterface
{
    /**
     * @var \Ict\RMA\Model\RmaPackageConditionFactory
     */
    protected $collection;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @param Ict\RMA\Model\RmaPackageConditionFactory $collection
     */
    public function __construct(
        \Ict\RMA\Model\RmaPackageConditionFactory $collection
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
        if (!$this->options) {
            $this->options = $this->collection->create()->getCollection()->toOptionArray();
        }
        return $this->options;
    }
}
