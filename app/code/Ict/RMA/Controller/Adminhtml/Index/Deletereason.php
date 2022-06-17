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

class Deletereason extends Action
{
    /**
     * @var \Ict\RMA\Model\RmaReason
     */
    protected $rmaReason;

    /**
     * @param Ict\RMA\Model\RmaReason $rmaReason
     * @param Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Ict\RMA\Model\RmaReason $rmaReason,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->rmaReason = $rmaReason;
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
        $id = $this->getRequest()->getParam('rma_reason_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $rmaReason = $this->rmaReason;
                $rmaReason->load($id);
                $rmaReason->delete();
                $this->messageManager->addSuccess(__('Record deleted successfully.'));
                return $resultRedirect->setPath('rma/index/reason');
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('rma/index/editreason', ['id' => $id]);
            }
        }
        $this->messageManager->addError(__('Record does not exist.'));
        return $resultRedirect->setPath('rma/index/reason');
    }
}
