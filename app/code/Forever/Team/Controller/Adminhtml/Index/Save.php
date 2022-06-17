<?php

namespace Forever\Team\Controller\Adminhtml\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Forever\Team\Model\TeamFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Session\SessionManagerInterface;

class Save extends Action
{

    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Forever_Team::team';

    /**
     * @var TeamFactory
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
     * @param \Forever\Team\Model\EntityFactory $entityFactory,
     * @param \Magento\Framework\Session\SessionManagerInterface $sessionManager
     */
    public function __construct(
        Context $context,
        TeamFactory $entityFactory,
        PageFactory  $resultPageFactory,
        SessionManagerInterface $sessionManager
    ) {
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
        
        try {
            if (!empty($data['id'])) {
                $entityModel->setId($data['id']);
            }
            $entityModel->setData('name', $data['name']);
            $entityModel->setData('status', $data['status']);
            $entityModel->setData('designation', $data['designation']);
            $entityModel->setData('orders', $data['orders']);
            if (isset($data['image'][0]['name'])) {
                $entityModel->setData('image', $data['image'][0]['name']);
            } else {
                $entityModel->setData('image', null);
            }
            $entityModel->save();
            //check for `back` parameter
            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath(
                    '*/*/edit',
                    ['id' => $entityModel->getId(),
                    '_current' => true,
                    '_use_rewrite' => true]
                );
            }

            $this->_redirect('*/*');
            $this->messageManager->addSuccess(__('The Team has been saved.'));

        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
    }
}
