<?php
/**
 * @author Ict Team
 * @copyright Copyright (c) 2017 Ict (http://icreativetechnologies.com/)
 * @package Ict_Shopbybrand
 */

namespace Ict\Shopbybrand\Block\Adminhtml\Catalog\Product\Edit\Tab;

use Magento\Backend\Block\Widget\Grid\Extended as ExtendedGrid;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Registry;
use Ict\Shopbybrand\Model\ResourceModel\Maker\CollectionFactory as MakerCollectionFactory;
use Ict\Shopbybrand\Model\Maker\Product as MakerProduct;
use Magento\Catalog\Controller\Adminhtml\Product\Builder as ProductBuilder;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Helper\Data as BackendHelper;

class Maker extends ExtendedGrid implements TabInterface
{

    protected $makerCollectionFactory;

    protected $makerProduct;

    protected $registry;

    protected $productBuilder;

    protected $_product;

    /**
     * @param MakerCollectionFactory $makerCollectionFactory
     * @param MakerProduct $makerProduct
     * @param Registry $registry
     * @param ProductBuilder $productBuilder
     * @param Context $context
     * @param BackendHelper $backendHelper
     * @param array $data
     */
    public function __construct(
        MakerCollectionFactory $makerCollectionFactory,
        MakerProduct $makerProduct,
        Registry $registry,
        ProductBuilder $productBuilder,
        Context $context,
        BackendHelper $backendHelper,
        array $data = []
    )
    {
        $this->makerCollectionFactory = $makerCollectionFactory;
        $this->makerProduct = $makerProduct;
        $this->registry = $registry;
        $this->productBuilder = $productBuilder;
        parent::__construct($context, $backendHelper, $data);
    }

    public function _construct()
    {
        parent::_construct();
        $this->setId('maker_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        if ($this->getProduct()->getId()) {
            $this->setDefaultFilter(['in_makers'=>1]);
        }
    }

    /**
     * prepare collection
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->makerCollectionFactory->create();
        if ($this->getProduct()->getId()) {
            $constraint = 'related.product_id='.$this->getProduct()->getId();
        } else {
            $constraint = 'related.product_id=0';
        }
        $collection->getSelect()->joinLeft(
            ['related' => $collection->getTable('ict_shopbybrand_maker_product')],
            'related.maker_id = main_table.maker_id AND '.$constraint,
            ['position']
        );
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * no mass action here
     * @return $this
     */
    protected function _prepareMassaction()
    {
        return $this;
    }

    /**
     * prepare columns
     * @return $this
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_makers',
            [
                'type'  => 'checkbox',
                'name'  => 'in_makers',
                'values'=> $this->_getSelectedMakers(),
                'align' => 'center',
                'index' => 'maker_id',
                'header_css_class' => 'col-select',
                'column_css_class' => 'col-select'
            ]
        );
        $this->addColumn(
            'name',
            [
                'header'=> __('Name'),
                'align' => 'left',
                'index' => 'name',
            ]
        );
        $this->addColumn(
            'position',
            [
                'header'        => __('Position'),
                'name'          => 'position',
                'width'         => 60,
                'type'        => 'number',
                'validate_class'=> 'validate-number',
                'index'         => 'position',
                'editable'      => true,
            ]
        );
        return parent::_prepareColumns();
    }

    /**
     * @return array
     */
    protected function _getSelectedMakers()
    {
        $makers = $this->getProductMakers();
        if (!is_array($makers)) {
            $makers = array_keys($this->getSelectedMakers());
        }
        return $makers;
    }

    /**
     * get selected makers
     * @return array
     */
    public function getSelectedMakers()
    {
        $makers = [];
        $selected = $this->makerProduct->getSelectedMakers($this->getProduct());
        if (!is_array($selected)) {
            $selected = [];
        }
        foreach ($selected as $maker) {
            /** @var \Ict\Shopbybrand\Model\Maker $maker */
            $makers[$maker->getId()] = ['position' => $maker->getPosition()];
        }
        return $makers;
    }

    /**
     * get row url
     * @param \Magento\Catalog\Model\Product|\Magento\Framework\Object $item
     * @return string
     */
    public function getRowUrl($item)
    {
        return '#';
    }

    /**
     * get grid url
     * @return string
     */
    public function getGridUrl()
    {
        return $this->_urlBuilder->getUrl(
            '*/*/makersGrid',
            [
                'id'=>$this->getProduct()->getId()
            ]
        );
    }

    /**
     * get current product
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        if (is_null($this->_product)) {
            if ($this->registry->registry('current_product')) {
                $this->_product = $this->registry->registry('current_product');
            } else {
                $product = $this->productBuilder->build($this->getRequest());
                $this->_product = $product;
            }
        }
        return $this->_product;
    }

    /**
     * @param \Magento\Backend\Block\Widget\Grid\Column $column
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_makers') {
            $makerIds = $this->_getSelectedMakers();
            if (empty($makerIds)) {
                $makerIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('maker_id', ['in'=>$makerIds]);
            } else {
                if ($makerIds) {
                    $this->getCollection()->addFieldToFilter('maker_id', ['nin'=>$makerIds]);
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getTabLabel()
    {
        return __('Shopbybrand');
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getTabUrl()
    {
        return $this->getUrl('ict_shopbybrand/catalog_product/makers', ['_current' => true]);
    }

    /**
     * @return string
     */
    public function getTabClass()
    {
        return 'ajax only';
    }
}
