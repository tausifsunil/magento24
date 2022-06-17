<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Forever\Testimonials\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

class Edit extends \Magento\Backend\App\Action
{
    /**
         * Authorization level of a basic admin session
         *
         * @see _isAllowed()
         */
        const ADMIN_RESOURCE = 'Forever_Testimonials::testimonials';

        /**
         * @var \Magento\Framework\Registry
         */
        private $coreRegistry;
    
        /**
         * @var \Forever\Testimonials\Model\TestimonialsFactory
         */
        private $entityFactory;
    
        /**
         * @param \Magento\Backend\App\Action\Context $context
         * @param \Magento\Framework\Registry $coreRegistry,
         * @param \Forever\Testimonials\Model\EntityFactory $entityFactory
         */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Forever\Testimonials\Model\TestimonialsFactory $entityFactory
    ) {
        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
        $this->entityFactory = $entityFactory;
    }
    public function execute()
    {
        $rowId = (int) $this->getRequest()->getParam('id');
        $rowData = $this->entityFactory->create();
        if ($rowId) {
            $rowData = $rowData->load($rowId);
            
            if (!$rowData->getId()) {
                $this->messageManager->addError(__('row data no longer exist.'));
                $this->_redirect('*/*/index');
                return;
            }
        }
        $this->coreRegistry->register('row_data', $rowData);
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Forever_Testimonials::first_level_menu');
        $title = "Testimonials Information";
        $resultPage->getConfig()->getTitle()->prepend($title);
        return $resultPage;
    }
}
