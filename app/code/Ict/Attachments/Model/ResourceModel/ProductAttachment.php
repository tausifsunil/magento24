<?php

namespace Ict\Attachments\Model\ResourceModel;

class ProductAttachment extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    const TABLE_NAME    = 'ict_product_attachment';
    const PRIMARY_KEY   = 'attachment_id';

    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, self::PRIMARY_KEY);
    }
}
