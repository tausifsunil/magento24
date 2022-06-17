<?php

namespace Ict\Attachments\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;

class Delete extends Action
{
    /**
     * @var \Ict\Attachments\Model\ProductAttachment
     */
    protected $productAttachment;

    /**
     * Ict\Attachments\Model\ProductAttachment $productAttachment
     */
    public function __construct(
        \Ict\Attachments\Model\ProductAttachment $productAttachment,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->productAttachment = $productAttachment;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Ict_Attachments::index_delete');
    }

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('attachment_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $productAttachment = $this->productAttachment;
                $productAttachment->load($id);
                $productAttachment->delete();
                $this->messageManager->addSuccess(__('The Attachment has been deleted successfully.'));
                return $resultRedirect->setPath('attachment/index/index');
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('attachment/index/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addError(__('Record does not exist.'));
        return $resultRedirect->setPath('attachment/index/index');
    }
}
