<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Forever\Blog\Controller\Adminhtml\Tag;

/**
 * @Class MassDelete
 * package Forever\CustomerReview\Controller\Adminhtml\Index
 */
class MassDelete extends \Magento\Backend\App\Action
{
    protected $tagFactory;
    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Forever\Blog\Model\TagFactory     $tagFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Forever\Blog\Model\TagFactory $tagFactory
    ) {
        $this->tagFactory = $tagFactory;
        parent::__construct($context);
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $selectedIds = $data['selected'];
        try {
            foreach ($selectedIds as $selectedId) {
                $deleteData = $this->tagFactory->create()->load($selectedId);
                $deleteData->delete();
            }
            $this->messageManager->addSuccess(__('Row data has been successfully deleted.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('blog/tag/index');
    }

    /**
     * @return bool
     */
    protected function isAllowed()
    {
        return $this->_authorization->isAllowed('Forever_Blog::home');
    }
}
