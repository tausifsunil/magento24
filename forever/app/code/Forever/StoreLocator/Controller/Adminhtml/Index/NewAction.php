<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Forever\StoreLocator\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Forever\StoreLocator\Model\StoreFactory;
use Magento\Framework\Controller\ResultFactory;
    
class NewAction extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Forever_StoreLocator::store';
    
    /**
     * @var \Forever\StoreLocator\Model\StoreFactory
     */
    private $storeFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Forever\StoreLocator\Model\StoreFactory $storeFactory
     */
    public function __construct(
        Context $context,
        StoreFactory $storeFactory
    ) {
        parent::__construct($context);
        $this->storeFactory = $storeFactory;
    }
    public function execute()
    {
        $this->storeFactory->create();
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Forever_StoreLocator::index');
        $title = "Store Information";
        $resultPage->getConfig()->getTitle()->prepend($title);
        return $resultPage;
    }
}
