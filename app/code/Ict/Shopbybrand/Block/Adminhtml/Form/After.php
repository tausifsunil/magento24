<?php
/**
 * @author Ict Team
 * @copyright Copyright (c) 2017 Ict (http://icreativetechnologies.com/)
 * @package Ict_Shopbybrand
 */

namespace Ict\Shopbybrand\Block\Adminhtml\Form;

class After extends \Magento\Backend\Block\Template
{
    
    protected $_registry;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_registry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Get current operation
     *
     * @return \Magento\Framework\Model\AbstractModel
     */
    public function getOperation()
    {
        return $this->_registry->registry('current_operation');
    }
}
