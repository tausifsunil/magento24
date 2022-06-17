<?php

namespace Forever\Team\Model\Team;

use Forever\Team\Model\ResourceModel\Team\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var storeManager
     */
    protected $storeManager;
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $entityCollectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        StoreManagerInterface $storeManager,
        CollectionFactory $entityCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $entityCollectionFactory->create();
        $this->storeManager = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        $this->loadedData = [];
        
        foreach ($items as $item) {
            $itemData = $item->getData();
            if (isset($itemData['image'])) {
                $imageName = $itemData['image'];
            
                $itemData['image'] = [
                    [
                        'name' => $imageName,
                        'url' => $this->storeManager->getStore()
                        ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA)
                        . 'team/image/' . $itemData['image'],
                    ],
                ];
            }
            $this->loadedData[$item->getId()] = $itemData;
        }
        return $this->loadedData;
    }
}
