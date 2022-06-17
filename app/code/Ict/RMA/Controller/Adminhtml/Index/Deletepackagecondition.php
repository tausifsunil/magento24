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

class Deletepackagecondition extends Action
{
    /**
     * @var \Ict\RMA\Model\RmaPackageCondition
     */
    protected $rmaPackageCondition;

    /**
     * @param Ict\RMA\Model\RmaPackageCondition $rmaPackageCondition
     * @param Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Ict\RMA\Model\RmaPackageCondition $rmaPackageCondition,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->rmaPackageCondition = $rmaPackageCondition;
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
        $id = $this->getRequest()->getParam('rma_package_condition_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $rmaPackageCondition = $this->rmaPackageCondition;
                $rmaPackageCondition->load($id);
                $rmaPackageCondition->delete();
                $this->messageManager->addSuccess(__('Record deleted successfully.'));
                return $resultRedirect->setPath('rma/index/packagecondition');
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('rma/index/editpackagecondition', ['id' => $id]);
            }
        }
        $this->messageManager->addError(__('Record does not exist.'));
        return $resultRedirect->setPath('rma/index/packagecondition');
    }
}
