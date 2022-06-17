<?php
 
namespace Grid\Banners\Block;
 
use Magento\Framework\View\Element\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
// use Magento\Store\Model\ScopeInterface;
use Grid\Banners\Model\ResourceModel\Blog\CollectionFactory; 
class Display extends Template
{
 
    public $collection;
     protected $filesystem;
     /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
 
    public function __construct(Context $context,\Magento\Framework\Filesystem $filesystem, StoreManagerInterface $storeManager,
    \Magento\Cms\Model\Template\FilterProvider $filter,CollectionFactory $collectionFactory, array $data = [])
    {
        $this->collection = $collectionFactory;
        $this->storeManager = $storeManager;
        $this->filesystem = $filesystem;
        $this->filter = $filter;

        parent::__construct($context, $data);
    }
 
    public function getCollection()
    {

        return $this->collection->create();

    }

    public function getLogo()
    {
       $url = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'banners/image/';
        return $url;
    }
    public function getStorename()
    {
         return $this->_scopeConfig->getValue(
        'general/store_information/name',
        \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    public function getContentFromStaticBlock($content)
    {
        return $this->filter->getBlockFilter()->filter($content);
    }
 
}

// namespace Grid\Banners\Block;
// use Magento\Backend\App\Action\Context;
// use Magento\Framework\Controller\ResultFactory;
// // use Magento\Ui\Component\MassAction\Filter;
// use Grid\Banners\Model\ResourceModel\Blog\CollectionFactory;
// class Display extends \Magento\Framework\View\Element\Template
// {
//     /**
//      * @var Filter
//      */
//     // protected $filter;
//     /**
//      * @var CollectionFactory
//      */
//     protected $collectionFactory;
//     /**
//      * @param Context           $context
//      * @param Filter            $filter
//      * @param CollectionFactory $collectionFactory
//      */
//     public function __construct(
//         Context $context,
//         // Filter $filter,
//         CollectionFactory $collectionFactory
//     ) {
//         // $this->filter = $filter;
//         $this->collectionFactory = $collectionFactory;
//         parent::__construct($context);
//     }
//     /**
//      * Execute action
//      *
//      * @return \Magento\Backend\Model\View\Result\Redirect
//      * @throws \Magento\Framework\Exception\LocalizedException|\Exception
//      */
//     public function Showdata()
//     {
//         // $params = $this->getRequest()->getParams('selected');
        
//         $collection=$this->collectionFactory->create();
//         $data=$collection->getData();
//         print_r($data);
//         die;
//     }
// }