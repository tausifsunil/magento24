<?php

namespace Ict\Attachments\Model\ProductAttachment;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $loadedData;
    protected $collectionFactory;

    public function __construct(
        \Ict\Attachments\Model\ResourceModel\ProductAttachment\CollectionFactory $collectionFactory,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->storeManager = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $model) {
            $this->loadedData[$model->getAttachmentId()] = $model->getData();

            if($model->getFile()) {
                $files = '';
                $files = explode(',', $model->getFile());
                // echo "<pre>-----"; print_r(explode(',', $files)); die;
                foreach ($files as $key => $value) {
                    $m['file'][$key]['name'] = $value;
                    $m['file'][$key]['url'] = $this->getMediaUrl().$value;
                    $m['file'][$key]['size'] = $this->getAttachmentImageSize($value);
                    // $m['file'][$key]['previewType'] = 'image';
                    // $m['file'][$key]['previewWidth'] = "500px";
                    // $m['file'][$key]['previewHeight'] = "500px";
                } 
                $fullData = $this->loadedData;
                $this->loadedData[$model->getAttachmentId()] = array_merge($fullData[$model->getAttachmentId()], $m);
                // $m['file'][0]['name'] = $model->getFile();
                // $m['file'][0]['url'] = $this->getMediaUrl().$model->getFile();

                $data = $this->dataPersistor->get('attachment_index_index');

                if (!empty($data)) {
                    $model = $this->collection->getNewEmptyItem();
                    $model->setData($data);
                    $this->loadedData[$model->getAttachmentId()] = $model->getData();
                    $this->dataPersistor->clear('attachment_index_index');
                }
            }
        }
        return $this->loadedData;
    }

    public function getMediaUrl()
    {
        // requestaquote is IMAGE_UPLOAD_DIRECTORY name.
        $mediaUrl = $this->storeManager->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'ictAttachment/';
        
        return $mediaUrl;
    }

    public function getAttachmentImageSize($file)
    {
        $img = str_replace($this->storeManager->getStore()->getBaseUrl(), '', $this->getMediaUrl());
        $fileSize = filesize($img . $file);
        $fileSize = round($fileSize, 2);
        return $fileSize;
    }
}
