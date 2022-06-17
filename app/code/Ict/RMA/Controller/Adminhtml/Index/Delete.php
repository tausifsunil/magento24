<?php

/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_RMA
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */

namespace Ict\RMA\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;

class Delete extends Action
{
    /**
     * @var \Ict\RMA\Model\Rma
     */
    protected $rma;

    /**
     * @var \Ict\RMA\Model\RmaMessage
     */
    protected $rmaMessage;

    /**
     * @param \Ict\RMA\Model\Rma $rma
     * @param \Ict\RMA\Model\RmaMessage $rmaMessage
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Ict\RMA\Model\Rma $rma,
        \Ict\RMA\Model\RmaMessage $rmaMessage,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->rma = $rma;
        $this->rmaMessage = $rmaMessage;
        parent::__construct($context);
    }

    /**
     * @inheritdoc
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Ict_RMA::index_delete');
    }

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('rma_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $this->rma->load($id);
                $this->rma->delete();
                $collection = $this->rmaMessage->getCollection()->addFieldToFilter('rma_id', ['eq' => $id]);
                foreach ($collection->getData() as $value) {
                    $this->rmaMessage->load($value['rma_message_id']);
                    $this->rmaMessage->delete();
                }
                $this->messageManager->addSuccess(__('Record deleted successfully.'));
                return $resultRedirect->setPath('rma/index/index');
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('rma/index/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addError(__('Record does not exist.'));
        return $resultRedirect->setPath('rma/index/index');
    }
}
