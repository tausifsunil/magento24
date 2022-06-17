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

class Savepackagecondition extends Action
{
    /**
     * @var \Ict\RMA\Model\RmaPackageCondition
     */
    protected $rmaPackageCondition;

    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $adminSession;

    /**
     * @param Ict\RMA\Model\RmaPackageCondition $rmaPackageCondition
     * @param Magento\Backend\Model\Session $adminSession
     * @param Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Ict\RMA\Model\RmaPackageCondition $rmaPackageCondition,
        \Magento\Backend\Model\Session $adminSession,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->rmaPackageCondition = $rmaPackageCondition;
        $this->adminSession = $adminSession;
        parent::__construct($context);
    }

    /**
     * Execute Save Data
     *
     * @return Controller | Save Data
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $id = $this->getRequest()->getParam('rma_package_condition_id');
            if ($id) {
                $this->rmaPackageCondition->load($id);
            }

            $this->rmaPackageCondition->setData($data);
            try {
                $this->rmaPackageCondition->save();
                $this->messageManager->addSuccess(__('The data has been saved.'));
                $this->adminSession->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    if ($this->getRequest()->getParam('back') == 'add') {
                        return $resultRedirect->setPath('rma/index/addpackagecondition');
                    } else {
                        return $resultRedirect->setPath(
                            'rma/index/editpackagecondition',
                            ['id' => $this->rmaPackageCondition->getRmaPackageConditionId(),
                            '_current' => true]
                        );
                    }
                }
                return $resultRedirect->setPath('rma/index/packagecondition');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
            }
            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath(
                'rma/index/packagecondition',
                ['id' => $this->getRequest()->getParam('rma_package_condition_id')]
            );
        }
        return $resultRedirect->setPath('rma/index/packagecondition');
    }
}
