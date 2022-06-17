<?php

namespace Ict\Attachments\Model;

class ProductAttachment extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Ict\Attachments\Model\ResourceModel\ProductAttachment::class);
    }
}
