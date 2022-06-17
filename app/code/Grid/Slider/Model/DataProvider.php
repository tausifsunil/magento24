<?php

namespace Grid\Slider\Model;

use Grid\Slider\Model\ResourceModel\Blog\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
// use RH\Helloworld\Model\ResourceModel\Helloworld\Collection;
// use Grid\Slider\Model\ResourceModel\Blog\Collection;
use Magento\Framework\App\RequestInterface;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var array
     */
    protected $collection;
    protected $loadedData;
    protected $request;
    protected $storeManager;
    // @codingStandardsIgnoreStart
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $blogCollectionFactory,
        // Collections $collection,
        RequestInterface $request,
        StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $blogCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        // $this->collection = $collection;
        $this->request = $request;
        $this->storeManager = $storeManager;
    }
    // @codingStandardsIgnoreEnd
    public function getData()
    {
        // $id = $this->getRequest()->getParam('blog_id');
        // print_r($id);
        // die();
        // print_r($this->loadedData->getData());
        // echo "<br>hello";
        // die();
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $item) {
            $this->loadedData[$item->getId()] = $item->getData();
        }   
        // print_r($this->loadedData);
        // die;
        // print_r($this->loadedData);
        // die();
       return $this->loadedData;
        }   
        
    }

 