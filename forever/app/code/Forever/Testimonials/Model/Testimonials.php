<?php
/** 
 * @package   Forever_Testimonials
 * @author    Ricky Thakur (Kapil Dev Singh)
 * @copyright Copyright (c) 2018 Ricky Thakur
 * @license   MIT license (see LICENSE.txt for details)
 */
namespace Forever\Testimonials\Model;

class Testimonials extends \Magento\Framework\Model\AbstractModel
{
    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'testimonials_records';

    /**
     * @var string
     */
    protected $_cacheTag = 'testimonials_records';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'testimonials_records';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('Forever\Testimonials\Model\ResourceModel\Testimonials');
    }    
}
