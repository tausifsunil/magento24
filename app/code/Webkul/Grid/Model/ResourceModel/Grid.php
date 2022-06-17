<?php
/**
 * Grid Grid ResourceModel.
 * @category    Webkul
 * @author      Webkul Software Private Limited
 */
namespace Webkul\Grid\Model\ResourceModel;

/**
 * Grid Grid mysql resource.
 */
class Grid extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';
    

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('wk_grid_records', 'entity_id');
    }
}