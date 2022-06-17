<?php

/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_RMA
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */

namespace Ict\RMA\Controller\Adminhtml\Index;

use Magento\Framework\Controller\ResultFactory;

class MassReasonDelete extends \Magento\Backend\App\Action
{
    /**
     * @var \Ict\RMA\Model\ResourceModel\RmaReason\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $filter;

    /**
     * @param Ict\RMA\Model\ResourceModel\RmaReason\CollectionFactory $collectionFactory
     * @param Magento\Ui\Component\MassAction\Filter $filter
     * @param Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Ict\RMA\Model\ResourceModel\RmaReason\CollectionFactory $collectionFactory,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->filter = $filter;
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
        $selected = $this->getRequest()->getParam('selected');
        $collection = $this->collectionFactory->create();
        if ($selected) {
            $collection->addFieldToFilter('rma_reason_id', ['in' => $selected]);
        }
        foreach ($collection as $item) {
            $item->delete();
        }
        $this->messageManager->addSuccess(__('A total of %1 element(s) have been deleted.', $collection->count()));
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('rma/index/reason');
    }
}
