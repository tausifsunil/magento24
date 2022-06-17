<?php
/** 
 * @package   Forever_Testimonials
 * @author    Ricky Thakur (Kapil Dev Singh)
 * @copyright Copyright (c) 2018 Ricky Thakur
 * @license   MIT license (see LICENSE.txt for details)
 */

namespace Forever\Testimonials\Model\ResourceModel;

/**
 * Modulename Entity mysql resource.
 */
class Testimonials extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Construct.
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     *
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    ) 
    {
        parent::__construct($context);
    }
 
    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('forever_testimonials', 'id');
    }
}