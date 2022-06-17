<?php
/**
 * Created By : RH
 */
namespace Grid\Slider\Block\Adminhtml\Tab;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Framework\App\ObjectManager;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Grid\Banners\Model\ResourceModel\Blog\CollectionFactory;
use Grid\slider\Model\ResourceModel\Blog\Collection;

class Productgrid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;
    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;
    /**
     * @var \RH\CustProductGrid\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollFactory;
    /**
     * @param \Magento\Backend\Block\Template\Context    $context
     * @param \Magento\Backend\Helper\Data               $backendHelper
     * @param \Magento\Catalog\Model\ProductFactory      $productFactory
     * @param \Magento\Framework\Registry                $coreRegistry
     * @param \Magento\Framework\Module\Manager          $moduleManager
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param Visibility|null                            $visibility
     * @param array                                      $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        StoreManagerInterface $storeManager,
         CollectionFactory $blogCollectionFactory,
         Collection $CollectionFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\Module\Manager $moduleManager,
        // \Magento\Store\Model\StoreManagerInterface $storeManager,
        Visibility $visibility = null,
        array $data = []
    ) {
        $this->productFactory = $productFactory;
        // $this->productCollFactory = $productCollFactory;
        $this->coreRegistry = $coreRegistry;
        $this->collection = $blogCollectionFactory->create();
        $this->slidercollection = $CollectionFactory;

        $this->moduleManager = $moduleManager;
        $this->storeManager = $storeManager;
        $this->_storeManager = $storeManager;
        $this->visibility = $visibility ?: ObjectManager::getInstance()->get(Visibility::class);
        parent::__construct($context, $backendHelper, $data);
    }
    
    // protected function _getStore()
    // {
    //     $storeId = (int) $this->getRequest()->getParam('store', 0);
    //     return $this->_storeManager->getStore($storeId);
    // }


    protected function _prepareCollection()
    {
        // $store = $this->_getStore();
        $collection = $this->collection;
                $this->setCollection($collection);
        return parent::_prepareCollection();
    
    }
    
    // protected function _getmediaurl()
    // {

    //      $items = $this->collection;
    //     foreach ($items as $item) {
    //         if (isset($item['logo'])) {
    //                 $imageName = $item['logo'];
    //                 $imageName = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'banners/image/' . $item['logo'];
    //                 $tmpname=$imageName;
    //                 $item['logo']='<img src="' . $tmpname . '" width="50" height="50">';
    //             } 
    //             // $item=$item['logo'];
    //         }
    //         return $item;
    // }

    // protected function _addColumnFilterToCollection()
    // {
    //     // if ($column->getId() == 'blog_id') {
    //     //     $productIds = $this->_getSelectedProducts();
    //     //     if (empty($productIds)) {
    //     //         $productIds = 0;
    //     //     }
    //     //     if ($column->getFilter()->getValue()) {
    //     //         $this->getCollection()->addFieldToFilter('blog_id', ['in' => $productIds]);
    //     //     } else {
    //     //         if ($productIds) {
    //     //             $this->getCollection()->addFieldToFilter('blog_id', ['nin' => $productIds]);
    //     //         }
    //     //     }
    //     // } else {
    //     //     parent::_addColumnFilterToCollection($column);
    //     // }
    //     // return $this;
    // }
    /**
     * @return Extended
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'id',
            [
                'type' => 'checkbox',
                'html_name' => 'id',
                'required' => true,
                'values' => $this->_getSelectedProducts(),
                'align' => 'center',
                'index' => 'id',
            ]
        );
        $this->addColumn(
            'blog_id',
            [
                'header' => __('id'),
                'index' => 'blog_id',
                // 'header_css_class' => 'col-id',
                // 'values' => $this->_getSelectedProducts(),
                // 'column_css_class' => 'col-id',
            ]
        );
        // $this->addColumn(
        //     'blog_id',
        //     [
        //         'header' => __('ID'),
        //         'width' => '50px',
        //         'index' => 'blog_id',
        //         'type' => 'number',
        //     ]
        // );
        $this->addColumn(
            'name',
            [
                'header' => __('Name'),
                'index' => 'name',
                'header_css_class' => 'col-type',
                'column_css_class' => 'col-type',
            ]
        );
        $this->addColumn(
            'logo',
            [
                'header' => __('Image'),
                'index' => 'logo',
                'renderer'  => '\Grid\Slider\Block\Adminhtml\Inquiry\Grid\Renderer\Image',
            ]
        );
        // $store = $this->_getStore();
        $this->addColumn(
            'discription',
            [
                'header' => __('Discription'),
                // 'type' => 'price',
                // 'currency_code' => $store->getBaseCurrency()->getCode(),
                'index' => 'discription',
                'header_css_class' => 'col-price',
                'column_css_class' => 'col-price',
            ]
        );
        $this->addColumn(
            'position',
            [
                'header' => __('Position'),
                'name' => 'position',
                'width' => 60,
                'type' => 'number',
                'validate_class' => 'validate-number',
                'index' => 'position',
                'editable' => true,
                'edit_only' => true,
            ]
        );
        return parent::_prepareColumns();
    }
    /**
     * @return string
     */
    // public function getGridUrl()
    // {
    //     return $this->getUrl('*/index/grids', ['_current' => true]);
    // }
    /**
     * @return array
     */
    protected function _getSelectedProducts()
    {
        $products = array_keys($this->getSelectedProducts());
        return $products;
    }
    /**
     * @return array
     */
    public function getSelectedProducts()
    {
        // print_r($this->loadedData);
        // di/
        // $id = $this->getRequest()->getParam('blog_id');
        // $id=explode(",",$id);

        // print_r($id);
        // echo "hello";
        
        // die();
         // $params = $this->getRequest()->getParams('selected');
        // $statusvalue = $this->getRequest()->getParam();

        // echo "<pre>";
        // print_r($params);
        // print_r($statusvalue);
        // die();
        // print_r($id);
        // echo "hello";
        // die();
        $model = $this->collection;
        // print_r($model->getData());
        // die();
        $grids = [];
        foreach ($model as $key => $value) {
            $grids[] = $value->getBlogId();
        }
        // print_r($grids);
        // die();
        // $prodId = [];
        // foreach ($grids as $obj) {
        //     $prodId[$obj] = ['position' => "0"];
        // }
        return $grids;
    }
}