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

class Save extends Action
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
     * @var \Magento\Backend\Model\Session
     */
    protected $adminSession;

    /**
     * @param Ict\RMA\Model\Rma $rma
     * @param Ict\RMA\Model\RmaMessage $rmaMessage
     * @param Magento\Backend\Model\Session $adminSession
     * @param Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Ict\RMA\Model\Rma $rma,
        \Ict\RMA\Model\RmaMessage $rmaMessage,
        \Magento\Backend\Model\Session $adminSession,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->rma = $rma;
        $this->rmaMessage = $rmaMessage;
        $this->adminSession = $adminSession;
        parent::__construct($context);
    }

    /**
     * Execute The Save Data
     *
     * @return Controller | Save Data
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $id = $this->getRequest()->getParam('rma_id');
            if ($id) {
                $this->rma->load($id);
            }
            $this->rma->setResolution($data['resolution']);
            $this->rma->setPackageCondition($data['package_condition']);
            $this->rma->setStatus($data['status']);
            $this->rma->setAdditionalInfo($data['message']);
            $this->rma->save();
            try {
                if ($data['message'] != '') {
                    $this->rmaMessage->setRmaId($data['rma_id']);
                    $this->rmaMessage->setMessage($data['message']);
                    $this->rmaMessage->setType('admin');
                }
                $this->rmaMessage->save();

                $this->messageManager->addSuccess(__('The data has been saved.'));
                $this->adminSession->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    if ($this->getRequest()->getParam('back') == 'add') {
                        return $resultRedirect->setPath('rma/index/add');
                    } else {
                        return $resultRedirect->setPath(
                            'rma/index/edit',
                            ['id' => $this->rma->getRmaId(),
                            '_current' => true]
                        );
                    }
                }
                return $resultRedirect->setPath('rma/index/index');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException(
                    $e,
                    __('Something went wrong while saving the data.')
                );
            }
            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('rma/index/index', ['id' => $this->getRequest()->getParam('rma_id')]);
        }
        return $resultRedirect->setPath('rma/index/index');
    }
}
