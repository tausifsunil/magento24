<?php
/**
 * @author Ict Team
 * @copyright Copyright (c) 2017 Ict (http://icreativetechnologies.com/)
 * @package Ict_Shopbybrand
 */

namespace Ict\Shopbybrand\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

class Maker extends Container
{
    
    protected function _construct()
    {
        $this->_controller = 'adminhtml_order';
        $this->_blockGroup = 'Ict_Shopbybrand';
        $this->_headerText = __('Shopbybrand');
        $this->_addButtonLabel = __('Create New Shopbybrand');
        parent::_construct();
    }
}
