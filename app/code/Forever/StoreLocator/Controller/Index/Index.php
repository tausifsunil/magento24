<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Forever\StoreLocator\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var _pageFactory
     */
    protected $pageFactory;
    /**
     * @param \Magento\Framework\App\Action\Context      $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        // $resultPage->getConfig()->getTitle()->prepend(__('Store'));
        return $resultPage;
    }
}
