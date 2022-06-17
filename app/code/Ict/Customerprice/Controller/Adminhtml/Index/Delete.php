<?php

/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_Customerprice
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */

namespace Ict\Customerprice\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;

class Delete extends Action
{
    /**
     * @var \Ict\Customerprice\Model\Customerprice
     */
    protected $customerprice;

    /**
     * @param \Ict\Customerprice\Model\Customerprice $customerprice
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Ict\Customerprice\Model\Customerprice $customerprice,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->customerprice = $customerprice;
        parent::__construct($context);
    }

    /**
     * @inheritdoc
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Ict_Customerprice::index_delete');
    }

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $customerprice = $this->customerprice;
                $customerprice->load($id);
                $customerprice->delete();
                $this->messageManager->addSuccess(__('Record deleted successfully.'));
                return $resultRedirect->setPath('customerprice/index/index');
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('customerprice/index/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addError(__('Record does not exist.'));
        return $resultRedirect->setPath('customerprice/index/index');
    }
}
