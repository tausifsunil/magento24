<?php

/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_RMA
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */

namespace Ict\RMA\Model\ResourceModel\RmaPackageCondition;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    public const RMA_PACKAGE_CONDITION_ID = 'rma_package_condition_id';
    public const NAME_FIELD = 'name';

    public $_idFieldName = 'rma_package_condition_id';

    /**
     * Initialize resource mode.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Ict\RMA\Model\RmaPackageCondition::class,
            \Ict\RMA\Model\ResourceModel\RmaPackageCondition::class
        );
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return parent::_toOptionArray(self::RMA_PACKAGE_CONDITION_ID, self::NAME_FIELD);
    }
}
