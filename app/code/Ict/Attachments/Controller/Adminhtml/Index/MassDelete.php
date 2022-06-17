<?php

namespace Ict\Attachments\Controller\Adminhtml\Index;

use Magento\Framework\Controller\ResultFactory;

class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * @var \Ict\Attachments\Model\ResourceModel\ProductAttachment\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $filter;

    /**
     * @param Ict\Attachments\Model\ResourceModel\ProductAttachment\CollectionFactory $collectionFactory
     * @param Magento\Ui\Component\MassAction\Filter $filter
     */
    public function __construct(
        \Ict\Attachments\Model\ResourceModel\ProductAttachment\CollectionFactory $collectionFactory,
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
                $collection->addFieldToFilter('attachment_id', ['in' => $selected]);
                
        }
        foreach ($collection as $item) {
            $item->delete();
        }
        $this->messageManager->addSuccess(__('A total of %1 attachment(s) has been deleted.', $collection->count()));
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('attachment/index/index');
    }
}
