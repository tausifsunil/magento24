<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Created By: MageDelight Pvt. Ltd.
 */
namespace Grid\Slider\Controller\Adminhtml\Index;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Grid\Slider\Model\ResourceModel\Blog\CollectionFactory;
class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * @var Filter
     */
    protected $filter;
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;
    /**
     * @param Context           $context
     * @param Filter            $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
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
        $resultRedirect = $this->resultRedirectFactory->create();
        $params = $this->getRequest()->getParams('selected');
        $collectionSize=0;
        if (array_key_exists('selected', $params)) {
                foreach ($params['selected'] as $key => $id) {                         
                    $model = $this->_objectManager->create('Grid\Slider\Model\Blog');
                    $model->load($id);
                    $collectionSize++;
                    // print_r($model->getData());
                    $model->delete();
                }
            }
        else
        {
            $collection = $this->collectionFactory->create();
            $collectionSize = $collection->getSize();
            foreach ($collection as $item) {
                $item->delete();
            }
        }
        // echo "<pre>";
        // print_r($params);
        // die;

        $this->messageManager->addSuccess(__('A total of %1 element(s) have been deleted.', $collectionSize));
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}