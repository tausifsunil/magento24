<?php

namespace Ict\Attachments\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;

class Save extends Action
{
    /**
     * @var \Ict\Attachments\Model\ProductAttachment
     */
    protected $productAttachment;

    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $adminSession;

    /**
     * @param Ict\Attachments\Model\ProductAttachment $productAttachment
     * @param Magento\Backend\Model\Session $adminSession
     */
    public function __construct(
        \Ict\Attachments\Model\ProductAttachment $productAttachment,
        \Magento\Backend\Model\Session $adminSession,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->productAttachment = $productAttachment;
        $this->adminSession = $adminSession;
        parent::__construct($context);
    }

    /**
     * @return Controller | Save Data
     */
    public function execute()
    {
        // echo "<pre>"; print_r($_FILES); die;
        // echo "<pre>"; print_r($this->getRequest()->getFiles()); die;
        $data = $this->getRequest()->getParams();
        // echo "<pre>"; print_r($data); die;
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $id = $this->getRequest()->getParam('attachment_id');
            if ($id) {
                $this->productAttachment->load($id);
            }
            $this->productAttachment->setName($data['name']);
            $this->productAttachment->setProducts($data['category_products']);
            try {
                // $extension = [];
                // $images = [];
                $images = '';
                $ext = '';
                $extension = '';
                foreach ($data['file'] as $key => $value) {
                    if (isset($value['url']) && isset($value['name'])) {
                        $images .= $value['name'] . ',';
                        $extension = pathinfo($value['name']);
                        $ext .= $extension['extension'] . ',';
                    }
                    // echo "Key : " . $key . "value : " . $value['name'];
                }
                $str = rtrim(implode(',',array_unique(explode(',', $ext))), ',');
                // echo rtrim($images, ',');
                // echo "<br>";
                // echo "----------- ".$str;
                // die;
                // if (isset($data['file'][0]['url']) && isset($data['file'][0]['name'])) {
                //     $data['name'] = $data['name'];
                //     $image = $data['file'][0]['name'];
                //     $extension = pathinfo($image);
                //     $data['file_ext'] = $extension['extension'];
                //     //$this->imageUploader->moveFileFromTmp($image);
                // }

                $this->productAttachment->setFile(rtrim($images, ','));
                $this->productAttachment->setFileExt($str);
                $this->productAttachment->save();
                $this->messageManager->addSuccess(__('The Attachment have been saved successfully.'));
                $this->adminSession->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    if ($this->getRequest()->getParam('back') == 'add') {
                        return $resultRedirect->setPath('attachment/index/add');
                    } else {
                        return $resultRedirect->setPath(
                            'attachment/index/edit',
                            ['id' => $this->productAttachment->getAttachmentId(),
                            '_current' => true]
                        );
                    }
                }
                return $resultRedirect->setPath('attachment/index/index');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException(
                    $e,
                    // __('Something went wrong while saving the attachment.')
                    $e->getMessage()
                );
            }
        }
        return $resultRedirect->setPath('attachment/index/index');
    }

    public function setImage($data){
        $images = '';
        $ext = '';
        $extension = '';
        foreach ($data['file'] as $key => $value) {
            if (isset($value['url']) && isset($value['name'])) {
                $images .= $value['name'] . ',';
                $extension = pathinfo($value['name']);
                $ext .= $extension['extension'] . ',';
            }
            // echo "Key : " . $key . "value : " . $value['name'];
        }
        $str = rtrim(implode(',',array_unique(explode(',', $ext))), ',');
        $this->productAttachment->setFile(rtrim($images, ','));
        $this->productAttachment->setFileExt($str);
        $this->productAttachment->save();
    }
}
