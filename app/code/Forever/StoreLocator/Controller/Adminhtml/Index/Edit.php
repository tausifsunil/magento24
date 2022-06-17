<?php

/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Forever\StoreLocator\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

class Edit extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Forever_StoreLocator::store';

        /**
         * @var \Magento\Framework\Registry
         */
    private $coreRegistry;
    
        /**
         * @var \Forever\StoreLocator\Model\StoreFactory
         */
    private $entityFactory;
    
        /**
         * @param \Magento\Backend\App\Action\Context $context
         * @param \Magento\Framework\Registry $coreRegistry,
         * @param \Forever\StoreLocator\Model\StoreFactory $storeFactory
         */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Forever\StoreLocator\Model\StoreFactory $storeFactory
    ) {
        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
        $this->storeFactory = $storeFactory;
    }
    public function execute()
    {
        $rowId = (int) $this->getRequest()->getParam('store_id');
        $rowData = $this->storeFactory->create();
        if ($rowId) {
            $rowData = $rowData->load($rowId);
            if (!$rowData->getStoreId()) {
                $this->messageManager->addError(__('row data no longer exist.'));
                $this->_redirect('*/*/index');
                return;
            }
        }
        $this->coreRegistry->register('row_data', $rowData);
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Forever_StoreLocator::index');
        $title = "Store Information";
        $resultPage->getConfig()->getTitle()->prepend($title);
        return $resultPage;
    }
}
