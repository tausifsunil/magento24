<?php

namespace Forever\StoreLocator\Controller\Index;

class Search extends \Magento\Framework\App\Action\Action
{
    /**
     * @var collectionFactory
     */
    protected $collectionFactory;

    /**
     * @var resultJsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @param \Magento\Framework\App\Action\Context      $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Forever\StoreLocator\Model\ResourceModel\Store\CollectionFactory $collectionFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        $ajaxData = $this->getRequest()->getParam('search');
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter(['name','street'], [
            ['like' => '%' . $ajaxData . '%'],
            ['like' => '%' . $ajaxData . '%']

        ]);
        foreach ($collection as $key => $value) {
            $response[] = [
                $value->getName() . ' ' . $value->getStreet() . ' ' . $value->getCity()  . ' , ' . $value->getState() . ' ,' . //phpcs:ignore
                $value->getCountry(),
                $value->getLatitude(),
                $value->getLongitude(),
                $value->getStoreId()
            ];
        }
        $this->getResponse()->setContent(json_encode($response));
    }
}
