<?php
/**
 * @author Ict Team
 * @copyright Copyright (c) 2017 Ict (http://icreativetechnologies.com/)
 * @package Ict_Shopbybrand
 */

namespace Ict\Shopbybrand\Model\ResourceModel\Maker;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Store\Model\Store;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Category;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'maker_id';
 
    protected $_eventPrefix = 'ict_shopbybrand_maker_collection';

    protected $_eventObject = 'maker_collection';

    protected $storeManager;

    protected $_joinedFields = [];

    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        StoreManagerInterface $storeManager,
        $connection = null,
        AbstractDb $resource = null
    )
    {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
        $this->storeManager = $storeManager;

    }

    /**
     * Define resource model
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Ict\Shopbybrand\Model\Maker', 'Ict\Shopbybrand\Model\ResourceModel\Maker');
        $this->_map['fields']['maker_id'] = 'main_table.maker_id';
        $this->_map['fields']['store_id'] = 'store_table.store_id';
    }

    /**
     * after collection load
     * @return $this
     */
    protected function _afterLoad()
    {
        $items = $this->getColumnValues('maker_id');
        $connection = $this->getConnection();
        if (count($items)) {
            $select = $connection->select()->from(
                ['maker_store' => $this->getTable('ict_shopbybrand_maker_store')]
            )
            ->where(
                'maker_store.maker_id IN (?)',
                $items
            );

            if ($result = $connection->fetchPairs($select)) {
                foreach ($this as $item) {
                    /** @var $item \Ict\Shopbybrand\Model\Maker */
                    if (!isset($result[$item->getData('maker_id')])) {
                        continue;
                    }
                    $item->setData('store_id', $result[$item->getData('maker_id')]);
                }
            }
        }
        return parent::_afterLoad();
    }

    /**
     * Add filter by store
     * @param int|\Magento\Store\Model\Store $store
     * @param bool $withAdmin
     * @return $this
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        if (!$this->getFlag('store_filter_added')) {
            if ($store instanceof Store) {
                $store = [$store->getId()];
            }

            if (!is_array($store)) {
                $store = [$store];
            }

            if ($withAdmin) {
                $store[] = Store::DEFAULT_STORE_ID;
            }

            $this->addFilter('store_id', ['in' => $store], 'public');
        }
        return $this;
    }

    /**
     * Join store relation table if there is store filter
     * @return void
     */
    protected function _renderFiltersBefore()
    {
        if ($this->getFilter('store_id')) {
            $this->getSelect()->join(
                ['store_table' => $this->getTable('ict_shopbybrand_maker_store')],
                'main_table.maker_id = store_table.maker_id',
                []
            )
            ->group('main_table.maker_id');
        }
        parent::_renderFiltersBefore();
    }

    /**
     * Get SQL for get record count.
     * Extra GROUP BY strip added.
     * @return \Magento\Framework\DB\Select
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(\Zend_Db_Select::GROUP);
        return $countSelect;
    }

    /**
     * @param $product
     * @return $this
     */
    public function addProductFilter($product)
    {
        if ($product instanceof Product) {
            $product = $product->getId();
        }
        if (!isset($this->_joinedFields['product'])) {
            $this->getSelect()->join(
                ['related_product' => $this->getTable('ict_shopbybrand_maker_product')],
                'related_product.maker_id = main_table.maker_id',
                ['position']
            );
            $this->getSelect()->where('related_product.product_id = ?', $product);
            $this->_joinedFields['product'] = true;
        }
        return $this;
    }

    /**
     * @param $category
     * @return $this
     */
    public function addCategoryFilter($category)
    {
        if ($category instanceof Category){
            $category = $category->getId();
        }
        if (!isset($this->_joinedFields['category'])) {
            $this->getSelect()->join(
                ['related_category' => $this->getTable('ict_shopbybrand_maker_category')],
                'related_category.maker_id = main_table.maker_id',
                ['position']
            );

            $this->getSelect()->where('related_category.category_id = ?', $category);
            $this->_joinedFields['category'] = true;
        }
        return $this;
    }

}
