<?php

/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_RMA
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */

namespace Ict\RMA\Model\ResourceModel;

class RmaPackageCondition extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public const TABLE_NAME    = 'rma_package_condition';
    public const PRIMARY_KEY   = 'rma_package_condition_id';

    /**
     * Initialize table
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, self::PRIMARY_KEY);
    }
}
