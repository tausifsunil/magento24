<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Forever\StoreLocator\Controller\Adminhtml\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Forever\StoreLocator\Model\StoreFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Session\SessionManagerInterface;

class Save extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see isAllowed()
     */
    const ADMIN_RESOURCE = 'Forever_StoreLocator::store';

    /**
     * @var StoreFactory
     */
    protected $entityFactory;
    
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var SessionManagerInterface
     */
    protected $sessionManager;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory,
     * @param \Forever\StoreLocator\Model\StoreFactory $storeFactory,
     * @param \Magento\Framework\Session\SessionManagerInterface $sessionManager
     */
    public function __construct(
        Context $context,
        StoreFactory $storeFactory,
        PageFactory $resultPageFactory,
        SessionManagerInterface $sessionManager
    ) {
        parent::__construct($context);
        $this->storeFactory = $storeFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->_sessionManager = $sessionManager;
    }
    /**
     * Save action
     */
    public function execute()
    {
        $resultRedirect     = $this->resultRedirectFactory->create();
        $storeModel        = $this->storeFactory->create();
        $data               = $this->getRequest()->getPost();
        // echo "<pre>"; print_r($data);die();
        if ($data) {
            $id = $this->getRequest()->getParam('store_id');
            if ($id) {
                $storeModel->load($id);
            }
            $storeModel->setName($data['name']);
            $storeModel->setUrlKey($data['url_key']);
            $storeModel->setStreet($data['street']);
            $storeModel->setCity($data['city']);
            $storeModel->setState($data['state']);
            $storeModel->setZip($data['zip']);
            $storeModel->setCountry($data['country']);
            $storeModel->setStatus($data['status']);
            $storeModel->setDescription($data['description']);
            $storeModel->setLatitude($data['latitude']);
            $storeModel->setLongitude($data['longitude']);
            try {
                $storeModel->save();
                $this->messageManager->addSuccess(__('Row data has been successfully saved.'));
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['store_id' => $storeModel->getStoreId(),
                    '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Model\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Data.'));
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError(__($e->getMessage()));
            }
            return $resultRedirect->setPath('*/*/edit', ['store_id' => $this->getRequest()->getParam('store_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
