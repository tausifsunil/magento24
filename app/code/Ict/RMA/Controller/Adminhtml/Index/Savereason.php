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
use Magento\Backend\Model\Session;
use Ict\RMA\Model\RmaReason;

class Savereason extends Action
{
    /**
     * @var \Ict\RMA\Model\RmaReason $rmaReason
     */
    protected $rmaReason;

    /**
     * @var \Magento\Backend\Model\Session $adminSession
     */
    protected $adminSession;

    /**
     * @param Ict\RMA\Model\RmaReason $rmaReason
     * @param Magento\Backend\Model\Session $adminSession
     * @param Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Ict\RMA\Model\RmaReason $rmaReason,
        \Magento\Backend\Model\Session $adminSession,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->rmaReason = $rmaReason;
        $this->adminSession = $adminSession;
        parent::__construct($context);
    }

    /**
     * Excute Save Data
     *
     * @return Controller | Save Data
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $id = $this->getRequest()->getParam('rma_reason_id');
            if ($id) {
                $this->rmaReason->load($id);
            }

            $this->rmaReason->setData($data);
            try {
                $this->rmaReason->save();
                $this->messageManager->addSuccess(__('The data has been saved.'));
                $this->adminSession->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    if ($this->getRequest()->getParam('back') == 'add') {
                        return $resultRedirect->setPath('rma/index/addreason');
                    } else {
                        return $resultRedirect->setPath(
                            'rma/index/editreason',
                            ['id' => $this->rmaReason->getRmaReasonId(),
                            '_current' => true]
                        );
                    }
                }
                return $resultRedirect->setPath('rma/index/reason');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
            }
            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath(
                'rma/index/reason',
                ['id' => $this->getRequest()->getParam('rma_reason_id')]
            );
        }
        return $resultRedirect->setPath('rma/index/reason');
    }
}
