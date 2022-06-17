<?php

/**
 * Grid Admin Cagegory Map Record Save Controller.
 * @category  Webkul
 * @package   Webkul_Grid
 * @author    Webkul
 * @copyright Copyright (c) 2010-2017 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\Grid\Controller\Adminhtml\Grid;
use Webkul\Grid\Model\ImageUploader;


class SaveButton extends \Magento\Backend\App\Action
{
    /**
     * @var \Webkul\Grid\Model\GridFactory
     */
    var $gridFactory;

    // protected $imageUploader;
   /* protected $fileSystem;//here added
    protected $uploaderFactory;//here added
    protected $adapterFactory;//here added*/


    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Webkul\Grid\Model\GridFactory $gridFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Webkul\Grid\Model\GridFactory $gridFactory
        // ImageUploader $imageUploader
       /* \Magento\Framework\Filesystem $fileSystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Framework\Image\AdapterFactory $adapterFactory*/
    ) {
        parent::__construct($context);
        $this->gridFactory = $gridFactory;
        // $this->imageUploader = $imageUploader;
    /*    $this->fileSystem = $fileSystem;
     $this->adapterFactory = $adapterFactory;
     $this->uploaderFactory = $uploaderFactory;*/
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        /* echo '<pre>';
         print_R($data);die;*/
        if (!$data) {
            $this->_redirect('grid/grid/addrow');
            return;
        }
        try {

             /*$uploaderFactory = $this->uploaderFactory->create(['fileId' => 'image']);
                $uploaderFactory->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                $imageAdapter = $this->adapterFactory->create();
                $uploaderFactory->setAllowRenameFiles(true);
                $uploaderFactory->setFilesDispersion(true);
                $mediaDirectory = $this->fileSystem->getDirectoryRead(DirectoryList::MEDIA);
                $destinationPath = $mediaDirectory->getAbsolutePath('pub/media/customizationimages/');
                $result = $uploaderFactory->save($destinationPath);*/

            $rowData = $this->gridFactory->create();
            // $hello=implode(',', $data)
            if (isset($data['image'][0]['name'])) {
                $data['image'] = $data['image'][0]['name'];
                // $this->imageUploader->moveFileFromTmp($data['image']);
            }else {
                $data['image'] = '';
            } 
            /*foreach($data as $key=>$val){
                // foreach($key as $value){
                echo'<pre>';
                // print_r($val);

                // }
            }
            print_r($data['image'][0]['name']);
            print_r($data['image'][0]['tmp_name']);*/
        // echo'<pre>';print_r($data);die();

            if (isset($data['id'])) {
                $rowData->setEntityId($data['id']);
            } 
            /*if (isset($data['image'][0]['name']) && isset($data['image'][0]['tmp_name'])) {
                $data['image'] = $data['image'][0]['name'];
                $this->imageUploader->moveFileFromTmp($data['image']);
            } elseif (isset($data['image'][0]['name']) && !isset($data['image'][0]['tmp_name'])) {
                $data['image'] = $data['image'][0]['name'];
            } else {
                $data['image'] = '';
            }*/

            /*if(isset($data['image']) && isset($data['image'][0]) && isset($data['image'][0]['name'])){
                
                $imagename = $data['imageUpload'][0]['name'];
                $data['imageUpload'] = $imagename;
            }*/

            $rowData->setData($data);
            $rowData->save();

            $this->messageManager->addSuccess(__('Row data has been successfully saved.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('grid/grid/index');
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_Grid::save_button');
            // echo'<pre>';print_r($rowData->getData());die();
    }
}