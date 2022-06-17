<?php

/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_RMA
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */

namespace Ict\RMA\Model\ResourceModel;

class RmaMessage extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public const TABLE_NAME    = 'rma_message';
    public const PRIMARY_KEY   = 'rma_message_id';

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
