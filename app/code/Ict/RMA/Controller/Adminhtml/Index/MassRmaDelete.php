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

class MassRmaDelete extends \Magento\Backend\App\Action
{
    /**
     * @var \Ict\RMA\Model\ResourceModel\Rma\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $filter;

    /**
     * @var \Ict\RMA\Model\ResourceModel\RmaMessage\CollectionFactory
     */
    protected $rmaMessage;

    /**
     * @param Ict\RMA\Model\ResourceModel\Rma\CollectionFactory $collectionFactory
     * @param Ict\RMA\Model\ResourceModel\RmaMessage\CollectionFactory $rmaMessage
     * @param Magento\Ui\Component\MassAction\Filter $filter
     * @param Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Ict\RMA\Model\ResourceModel\Rma\CollectionFactory $collectionFactory,
        \Ict\RMA\Model\ResourceModel\RmaMessage\CollectionFactory $rmaMessage,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->rmaMessage = $rmaMessage;
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
        $collectionMessage = $this->rmaMessage->create();
        if ($selected) {
            $collection->addFieldToFilter('rma_id', ['in' => $selected]);
            $collectionMessage->addFieldToFilter('rma_id', ['in' => $selected]);
        }
        foreach ($collection as $item) {
            $item->delete();
        }
        foreach ($collectionMessage as $item) {
            $item->delete();
        }
        $this->messageManager->addSuccess(__('A total of %1 element(s) have been deleted.', $collection->count()));
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('rma/index/index');
    }
}
