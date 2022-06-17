<?php

/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_RMA
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */

namespace Ict\RMA\Model\ResourceModel;

class Rma extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public const TABLE_NAME    = 'rma';
    public const PRIMARY_KEY   = 'rma_id';

     /**
      * Initialize resource model
      *
      * @return void
      */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, self::PRIMARY_KEY);
    }
}
