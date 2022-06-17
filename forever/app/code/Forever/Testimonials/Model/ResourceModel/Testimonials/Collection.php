<?php
/** 
 * @package   Forever_Testimonials
 * @author    Ricky Thakur (Kapil Dev Singh)
 * @copyright Copyright (c) 2018 Ricky Thakur
 * @license   MIT license (see LICENSE.txt for details)
 */

namespace Forever\Testimonials\Model\ResourceModel\Testimonials;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * Load data for preview flag
     *
     * @var bool
     */
    protected $_previewFlag;

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'testimonials_entity_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'entity_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Forever\Testimonials\Model\Testimonials', 'Forever\Testimonials\Model\ResourceModel\Testimonials');
	}
}