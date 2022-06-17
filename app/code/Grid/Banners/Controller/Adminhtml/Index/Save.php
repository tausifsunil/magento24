<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Created By: MageDelight Pvt. Ltd.
 */
namespace Grid\Banners\Controller\Adminhtml\Index;
use Magento\Backend\App\Action;
use Magento\Backend\Model\Session;
use Grid\Banners\Model\Blog;
class Save extends \Magento\Backend\App\Action
{
    /*
     * @var Blog
     */
    protected $uiExamplemodel;
    /**
     * @var Session
     */
    protected $adminsession;
    /**
     * @param Action\Context $context
     * @param Blog           $uiExamplemodel
     * @param Session        $adminsession
     */
    public function __construct(
        Action\Context $context,
        Blog $uiExamplemodel,
        Session $adminsession
    ) {
        parent::__construct($context);
        $this->uiExamplemodel = $uiExamplemodel;
        $this->adminsession = $adminsession;
    }
    /**
     * Save blog record action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        // echo "<pre>";                                                       
        // print_r($data);

        if( array_key_exists('logo', $data))
        {
            $data['logo']=$data['logo'][0]['name'];
        }
        
        // print_r($data);
        // die();
        // $data['logo']=$data['logo'];
        // print_r($data);
        // echo sizeof($data);
        // if(sizeof($data)==7)
        // {
        //     echo '<br>hello';
        // }
        // else
        // {
        //     echo "image deleted";
        // }
        // die();
        // if(sizeof($data)!=7)
        //  {
        //         // echo "hello";
        //         $data['logo']=null;
        //         // echo $data['logo'];
        // }
        // else
        // {
        //         $data['logo']=$data['logo'][0]['name'];
        // } 
        // elseif($data['logo'])
        //     {
        //         // echo "hello";
        //         $data['logo']=$data['logo'][0]['name'];
        //         // echo $data['logo'];
        //     }
        // echo "<pre>";
        // print_r($data);
        // die;
        // print_r($data);
        // die;
        // echo "hyy";
        // echo $data['logo'][0]['name'];
        // die;

                // echo "hello";
                // die();
        // unset/($data['logo']);
        // echo"<pre>";
        // print_r(json_decode(json_encode($data),1));
        // die();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $blog_id = $this->getRequest()->getParam('blog_id');
            // print_r($blog_id);
            // die();
            if ($blog_id) {
                $this->uiExamplemodel->load($blog_id);
            }
            $this->uiExamplemodel->setData($data);
            // die();
            try {
                $this->uiExamplemodel->save();
                $this->messageManager->addSuccess(__('The data has been saved.'));
                $this->adminsession->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    if ($this->getRequest()->getParam('back') == 'add') {
                        return $resultRedirect->setPath('*/*/add');
                    } else {
                        return $resultRedirect->setPath('*/*/edit', ['blog_id' => $this->uiExamplemodel->getBlogId(), '_current' => true]);
                    }
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
            }
            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['blog_id' => $this->getRequest()->getParam('blog_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}