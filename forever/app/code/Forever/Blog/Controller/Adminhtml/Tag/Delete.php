<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Forever\Blog\Controller\Adminhtml\Tag;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * @var $resultPageFactory
     */
    protected $resultPageFactory;
    /**
     * @var $blogFactory
     */
    protected $tagFactory;
    /**
     * @param \Magento\Backend\App\Action\Context        $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Forever\Blog\Model\TagFactory            $tagFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Forever\Blog\Model\TagFactory $tagFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->tagFactory = $tagFactory;
        parent::__construct($context);
    }
    public function execute()
    {
        $id = $this->getRequest()->getParam('tag_id');
        $contact = $this->tagFactory->create()->load($id);
        if (!$contact) {
            $this->messageManager->addError(__('Unable to process. please, try again.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/tag/index', ['_current' => true]);
        }
        try {
            $contact->delete();
            $this->messageManager->addSuccess(__('Your data row has been deleted !'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__('Error while trying to delete row'));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/tag/index', ['_current' => true]);
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/tag/index', ['_current' => true]);
    }
}
