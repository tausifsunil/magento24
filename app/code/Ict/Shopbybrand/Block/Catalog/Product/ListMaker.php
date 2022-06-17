<?php
/**
 * @author Ict Team
 * @copyright Copyright (c) 2017 Ict (http://icreativetechnologies.com/)
 * @package Ict_Shopbybrand
 */

namespace Ict\Shopbybrand\Block\Catalog\Product;

use Ict\Shopbybrand\Model\Maker;
use Ict\Shopbybrand\Model\Maker\Product as MakerProduct;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\BlockFactory;

class ListMaker extends Template
{

    protected $_storeManager;

    protected $registry;

    protected $makerProduct;

    protected $blockFactory;

    protected $makerCollection;

    public function __construct(
        MakerProduct $makerProduct,
        Registry $registry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        BlockFactory $blockFactory,
        Context $context,
        array $data = []
    )
    {
        $this->makerProduct = $makerProduct;
        $this->_storeManager = $storeManager;
        $this->registry = $registry;
        $this->blockFactory = $blockFactory;
        parent::__construct($context, $data);
        $this->setTabTitle();
    }

    /**
     * @return \Ict\Shopbybrand\Model\ResourceModel\Maker\Collection
     */
    public function getMakerCollection()
    {
        if (is_null($this->makerCollection)) {
            $collection = $this->makerProduct->getSelectedMakersCollection($this->getProduct());
            $collection->addStoreFilter($this->_storeManager->getStore()->getId());
            $collection->addFieldToFilter('is_active', Maker::STATUS_ENABLED);
            $collection->getSelect()->order('position');
            $this->makerCollection = $collection;
        }
        return $this->makerCollection;
    }

    /**
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        return $this->registry->registry('current_product');
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        /** @var \Magento\Theme\Block\Html\Pager $pager */
        $pager = $this->getLayout()->createBlock('Magento\Theme\Block\Html\Pager');
        $pager->setNameInLayout('ict_shopbybrand.maker.list.pager');
        $pager->setPageVarName('p-maker');
        $pager->setLimitVarName('l-maker');
        $pager->setFragment('catalog.product.list.ict.shopbybrand.maker');
        $pager->setCollection($this->getMakerCollection());
        $this->setChild('pager', $pager);
        return parent::_prepareLayout();
    }

    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * @return tab title on PDP page
     */
    public function setTabTitle()
    {
        $title = $this->getCollectionSize()
            ? __('Shopbybrand %1', '<span class="counter">' . $this->getCollectionSize() . '</span>')
            : __('Shopbybrand');
        $this->setTitle($title);
        return $this;
    }

    /**
     * @return count
     */
    public function getCollectionSize()
    {
        return $this->getMakerCollection()->getSize();
    }

    /**
     * @return media url for brands
     */ 
    public function getBaseUrl()
    { 
        return $this->_storeManager->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
            ).'ict/shopbybrand/maker/image' ;
    }
}
