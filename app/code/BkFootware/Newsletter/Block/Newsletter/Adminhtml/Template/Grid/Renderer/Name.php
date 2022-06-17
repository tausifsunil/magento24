<?php

/**
 * newsletter customername
 */

namespace BkFootware\Newsletter\Block\Newsletter\Adminhtml\Template\Grid\Renderer;

use Magento\Framework\DataObject;

class Name extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * @return row
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        if ($row->getData('type') == 1) {
            return ($row->getData('name') != '' ? $row->getData('name') : '----');
        } else {
            return ($row->getData('name') != '' ? $row->getData('name') : '----');
        }
    }
}
