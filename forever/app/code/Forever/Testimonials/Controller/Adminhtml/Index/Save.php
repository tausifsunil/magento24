<?php
/** 
 * @package   Forever_Testimonials
 * @author    Ricky Thakur (Kapil Dev Singh)
 * @copyright Copyright (c) 2018 Ricky Thakur
 * @license   MIT license (see LICENSE.txt for details)
 */

namespace Forever\Testimonials\Controller\Adminhtml\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Forever\Testimonials\Model\TestimonialsFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Session\SessionManagerInterface;

class Save extends Action
{

    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Forever_Testimonials::testimonials';

    /**
     * @var TestimonialsFactory
    */
    protected $_entityFactory;
    
    /**
     * @var PageFactory
    */
    protected $resultPageFactory;

    /**
     * @var SessionManagerInterface
    */
    protected $_sessionManager;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory,
     * @param \Forever\Testimonials\Model\EntityFactory $entityFactory,
     * @param \Magento\Framework\Session\SessionManagerInterface $sessionManager
     */
    public function __construct(
        Context $context,
        TestimonialsFactory $entityFactory,
        PageFactory  $resultPageFactory,
        SessionManagerInterface $sessionManager
    )
    {
        parent::__construct($context);
        $this->_entityFactory = $entityFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->_sessionManager = $sessionManager;
        
    }
    
    /**
     * Save action
    */
    public function execute()
    {   
        $resultRedirect     = $this->resultRedirectFactory->create();
        $entityModel        = $this->_entityFactory->create();
        $data               = $this->getRequest()->getPost(); 
        // echo "<pre>";print_r($data);die();
        
        try{
            if (!empty($data['id'])) {
                $entityModel->setId($data['id']);
            }
            $entityModel->setData('name', $data['name']);
            $entityModel->setData('status', $data['status']);
            $entityModel->setData('message', $data['message']);
            if (isset($data['image'][0]['name'])) {
                        $entityModel->setData('image', $data['image'][0]['name']);
            } else {
                        $entityModel->setData('image',null);
            }
            // $entityModel->setData('image', $data['image'][0]['name']);
            $entityModel->save();
            //check for `back` parameter
            if ($this->getRequest()->getParam('back')) { 
                return $resultRedirect->setPath('*/*/edit', ['id' => $entityModel->getId(), '_current' => true, '_use_rewrite' => true]);
            }

            $this->_redirect('*/*');
            $this->messageManager->addSuccess(__('The Testimonials has been saved.'));

        }catch(\Exception $e){
            $this->messageManager->addError(__($e->getMessage()));
        }        
        
    }
}