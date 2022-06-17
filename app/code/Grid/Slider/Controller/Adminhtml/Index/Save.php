<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Created By: MageDelight Pvt. Ltd.
 */
namespace Grid\Slider\Controller\Adminhtml\Index;
use Magento\Backend\App\Action;
use Magento\Backend\Model\Session;
use Grid\Slider\Model\Blog;
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
        // echo '<pre>';
        $id = $this->getRequest()->getParam('id');
        $string=$data['blog_id'];
        $x=json_decode($string,true);
        $x=implode(",", $x);
        $data['blog_id']=$x;
        
        // $res = preg_replace('/[{}\@\.\;\" "]+/', '', $string);
        // $res=str_replace(",",":",$res);
        // $res=explode(":",$res);
        // $res=array_unique($res);
        // $res=implode(",", $res);
        

         // print_r($data);
         // die();


        // $unserialized_array = unserialize($string);
        // print_r($unserialized_array);
        // $comma_separated = join(', ', $unserialized_array);
        // echo $unserialized_array;
        // $newstr = filter_var($data['rh_products'], FILTER_SANITIZE_STRING);
        // echo $newstr;
        // print_r (explode(" ",$data['rh_products']));
        // print_r($id);
        // print_r(array_keys((json_encode($data['rh_products']))));

        // die;

        // $params = $this->getRequest()->getParams('blog_id');

        
        // print_r($params);
        // echo "<br>";
        // print_r($data);
        // echo "hello";
        // die();
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
        // if (sizeof($data)==7) 
        // {
        //         // echo "hello";
        //         $data['logo']=$data['logo'][0]['name'];
        //         // echo $data['logo'];
        // }

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
            $id = $this->getRequest()->getParam('id');
            // print_r($id);
            // echo "hello";
            // die();
            if ($id) {
                $this->uiExamplemodel->load($id);
            }
            // print_r($x);
            // die();
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
                        return $resultRedirect->setPath('*/*/edit', ['id' => $this->uiExamplemodel->getId(), '_current' => true]);
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
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/edit');
    }
}