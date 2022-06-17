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

class Save extends Action
{
    /**
     * @var \Ict\Customerprice\Model\Customerprice
     */
    protected $customerprice;

    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $adminSession;

    /**
     * @param \Ict\Customerprice\Model\Customerprice $customerprice
     * @param \Magento\Backend\Model\Session $adminSession
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Ict\Customerprice\Model\Customerprice $customerprice,
        \Magento\Backend\Model\Session $adminSession,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->customerprice = $customerprice;
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
            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $this->customerprice->load($id);
            }
            $this->customerprice->setData($data);
            try {
                $this->customerprice->save();
                $this->messageManager->addSuccess(__('The data has been saved.'));
                $this->adminSession->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    if ($this->getRequest()->getParam('back') == 'add') {
                        return $resultRedirect->setPath('customerprice/index/add');
                    } else {
                        return $resultRedirect->setPath(
                            'customerprice/index/edit',
                            ['id' => $this->customerprice->getId(),
                            '_current' => true]
                        );
                    }
                }
                return $resultRedirect->setPath('customerprice/index/index');
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
            return $resultRedirect->setPath('customerprice/index/index', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('customerprice/index/index');
    }
}
