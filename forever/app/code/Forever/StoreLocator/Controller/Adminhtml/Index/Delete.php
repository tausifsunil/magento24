<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Forever\StoreLocator\Controller\Adminhtml\Index;

class Delete extends \Magento\Backend\App\Action
{
    protected $storeFactory;
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Forever_StoreLocator::entity';

    /**
     * Delete action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Forever\StoreLocator\Model\StoreFactory $storeFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->storeFactory = $storeFactory;
        parent::__construct($context);
    }
    public function execute()
    {
        $id = $this->getRequest()->getParam('store_id');

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                // init model and delete
                $model = $this->storeFactory->create();
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccess(__('Store has been deleted.'));
                // go to grid
                
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['store_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('We can\'t find store to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
