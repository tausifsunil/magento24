<?php

namespace Ict\Attachments\Model\ResourceModel\ProductAttachment;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'attachment_id';

    protected function _construct()
    {
        $this->_init(
            \Ict\Attachments\Model\ProductAttachment::class,
            \Ict\Attachments\Model\ResourceModel\ProductAttachment::class
        );
    }
}
