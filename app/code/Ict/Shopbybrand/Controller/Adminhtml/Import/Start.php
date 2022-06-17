<?php
/**
 * @author Ict Team
 * @copyright Copyright (c) 2017 Ict (http://icreativetechnologies.com/)
 * @package Ict_Shopbybrand
 */

namespace Ict\Shopbybrand\Controller\Adminhtml\Import;

use Ict\Shopbybrand\Controller\Adminhtml\ImportResult as ImportResultController;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Filesystem\Io\File;
use Ict\Shopbybrand\Model\Maker\Image as ImageModel;
use Ict\Shopbybrand\Model\Upload;
use Ict\Shopbybrand\Model\ResourceModel\Maker\CollectionFactory as MakerCollectionFactory;

class Start extends ImportResultController
{

    protected $importModel;

    protected $_coreRegistry = null;

    protected $_fileCsv;

    protected $import_model;

    protected $imageModel;

    protected $uploadModel;

    protected $makerCollectionFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        Filesystem $fileSystem,
        MakerCollectionFactory $makerCollectionFactory,
        UploaderFactory $uploaderFactory,
        ImageModel $imageModel,
        Upload $uploadModel,
        File $io,
        \Magento\Framework\File\Csv $fileCsv,
        \Magento\Framework\Registry $registry,
        \Ict\Shopbybrand\Model\Maker $import_model
    ) {
        $this->_fileCsv = $fileCsv;
        $this->_coreRegistry = $registry;
        $this->imageModel = $imageModel;
        $this->makerCollectionFactory = $makerCollectionFactory;
        $this->uploadModel = $uploadModel;  
        $this->import_model = $import_model;
        parent::__construct($context, $fileSystem, $uploaderFactory, $io, $fileCsv, $registry);
    }

    /**
     * Start import process action
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            /** @var \Magento\Framework\View\Result\Layout $resultLayout */
            $resultLayout = $this->resultFactory->create(ResultFactory::TYPE_LAYOUT);
            /** @var $resultBlock \Magento\ImportExport\Block\Adminhtml\Import\Frame\Result */
            $resultBlock = $resultLayout->getLayout()->getBlock('import.frame.result');

            $resultBlock
            ->addAction('show', 'import_validation_container')
            ->addAction('innerHTML', 'import_validation_container_header', __('Status'))
            ->addAction('hide', ['edit_form', 'upload_button', 'messages']);

            $csv_data = $this->_fileCsv->getData($data["uploaded_file"]);
            $tmp = array_shift($csv_data);
            
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $mediaUrl = $objectManager->get('Magento\Store\Model\StoreManagerInterface')
                        ->getStore()
                        ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

            $fileSystem = $objectManager->create('\Magento\Framework\Filesystem');
            $mediaPath=$fileSystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath();
            $row = 0;
            $updates = 0;
            $total = 0;
            if (empty($csv_data)) {
                $this->error[] = __('CSV File is empty');
                $this->error_cnt++;
            } else {
                foreach ($csv_data as $key => $value) {
                    $makers = $this->makerCollectionFactory->create()->addFieldToFilter("name", array("eq"=>$value[0]));
                    if(count($makers->getData())>0){
                        foreach ($makers as $key ) {
                            if(!is_dir($mediaUrl."ict/import")) {
                                mkdir($mediaUrl."ict/import" , 0777);   
                            }
                            if(!is_dir($mediaUrl."ict/shopbybrand/maker/image")) {
                                mkdir($mediaUrl."ict/shopbybrand/maker/image" , 0777);   
                            }
                            $fb_image_url = $mediaUrl."ict/import".$value[3];
                            $fb_image_urls = $mediaUrl."ict/import".$value[3];
                            if(!file_exists($fb_image_url)){
                                $result = file_get_contents($fb_image_url);
                                $filename = $value[3];
                                file_put_contents($mediaPath.'ict/shopbybrand/maker/image'.$filename, $result);
                            }
                            if(!file_exists($fb_image_urls)){
                                $results = file_get_contents($fb_image_urls);
                                $filenames = $value[4];
                                file_put_contents($mediaPath.'ict/shopbybrand/maker/image'.$filenames, $results);
                            }
                            $makers = $objectManager->create('Ict\Shopbybrand\Model\Maker')->load($key->getId());
                            $makers->setUrlKey($value[1]);
                            $makers->setBannerText($value[2]);
                            $makers->setAvatar($value[3]);
                            $makers->setLogobanner($value[4]);
                            $makers->setIsActive($value[5]);
                            $makers->setIsFeatured($value[6]);
                            $makers->save();
                            $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
                            $connection = $resource->getConnection();
                            $tableName = $resource->getTableName('ict_shopbybrand_maker_store'); //gives table name with prefix
                             
                            //Insert Data into table
                            $sql = "INSERT INTO " . $tableName . " (maker_id, store_id) Values (".$makers->getId().",'0')";
                            $connection->query($sql);
                            $updates++;
                        }
                    } else {
                        $fb_image_url = $mediaUrl."ict/import".$value[3];
                        $fb_image_urls = $mediaUrl."ict/import".$value[3];
                        if(!file_exists($fb_image_url)){
                            $result = file_get_contents($fb_image_url);
                            $filename = $value[3];
                            file_put_contents($mediaPath.'ict/shopbybrand/maker/image'.$filename, $result);
                        }
                        if(!file_exists($fb_image_urls)){
                            $results = file_get_contents($fb_image_urls);
                            $filenames = $value[4];
                            file_put_contents($mediaPath.'ict/shopbybrand/maker/image'.$filenames, $results);
                        }
                        $makers = $objectManager->create('Ict\Shopbybrand\Model\Maker');
                        $makers->setName($value[0]);
                        $makers->setUrlKey($value[1]);
                        $makers->setBannerText($value[2]);
                        $makers->setAvatar($value[3]);
                        $makers->setLogobanner($value[4]);
                        $makers->setIsActive($value[5]);
                        $makers->setIsFeatured($value[6]);
                        $makers->save();
                        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
                        $connection = $resource->getConnection();
                        $tableName = $resource->getTableName('ict_shopbybrand_maker_store'); //gives table name with prefix
                         
                        //Insert Data into table
                        $sql = "INSERT INTO " . $tableName . " (maker_id, store_id) Values (".$makers->getId().",'0')";
                        $connection->query($sql);
                        $row++;
                    }
                $total++;
                }

                $response = ["total" => $total,"inserted" => $row, "updated" => $updates];
            }

            if (!empty($response)) {
                
                $write_msg = [];
                $write_msg[] = __('Shopbybrand Import successfully done.');
                $write_msg[] = __('Total : '.$response["total"].' Shopbybrand uploaded.');
                $write_msg[] = __('Total : '.$response["inserted"].' Shopbybrand inserted.');
                $write_msg[] = __('Total : '.$response["updated"].' Shopbybrand updated.');
                $resultBlock->addSuccess(implode("<br/>", $write_msg));
            }

            $this->addErrorMessages($resultBlock);
            return $resultLayout;
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('ict_shopbybrand/*/index');
        return $resultRedirect;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Ict_Shopbybrand::makers');
    }
}
