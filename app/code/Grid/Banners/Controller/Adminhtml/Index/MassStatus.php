<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Created By: MageDelight Pvt. Ltd.
 */
namespace Grid\Banners\Controller\Adminhtml\Index;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Grid\Banners\Model\ResourceModel\Blog\CollectionFactory;
class MassStatus extends \Magento\Backend\App\Action
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
        $params = $this->getRequest()->getParams('selected');
        $statusvalue = $this->getRequest()->getParam('status', 0);
        $collection=$this->collectionFactory->create();

        if (array_key_exists('selected', $params)) {
                for($count=0;$count<sizeof($params['selected']);$count++)
                {
                    $id[]=$params['selected'][$count];
                }
                $collection = $this->collectionFactory->create()->addFieldToFilter('blog_id',['in' => $id]);
                foreach($collection as $item)
                {
                    $item->setStatus($statusvalue);
                    $item->save();
                }
              
        }
        else
        {
            foreach($collection as $item)
            {
                $item->setStatus($statusvalue);
                $item->save();
            }
              
        }
        $this->messageManager->addSuccess(__('A total of %1 record(s) have been modified.', $collection->getSize()));
        // * @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect 
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }   
}