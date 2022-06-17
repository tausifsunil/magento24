<?php
/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_Quickorder
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */
 
namespace Ict\Quickorder\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    protected $resultPageFactory;
    
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollection
     */
    protected $productCollectionFactory;
    
    /**
     * @param Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        array $data = []
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        parent::__construct($context);
    }
    
    /**
     * Find product as per search result.
     */
    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        $resultPage = $this->resultPageFactory->create();
        $sku = $this->getRequest()->getParam('sku');
        if ($sku != "") {
            try {
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $productCollection = $this->productCollectionFactory->create();
                $productCollection->addAttributeToFilter(
                    [
                        ['attribute' => 'name', 'eq' => $sku],
                        ['attribute' => 'sku', 'eq' => $sku],
                    ]
                )->addAttributeToFilter('visibility', ['neq'=>'1'])->getFirstItem();

                if (empty($productCollection->getData())) {
                    $productCollectionSuggetion = $this->productCollectionFactory->create();
                    $productCollectionSuggetion->addAttributeToSelect('sku')
                    ->addAttributeToFilter('name', ['like' => '%'.$sku.'%'])
                    ->addAttributeToFilter('visibility', ['neq'=>'1'])->setPageSize(10);
                    if (!empty($productCollectionSuggetion->getData())) {
                        $block = $resultPage->getLayout()
                            ->createBlock(\Ict\Quickorder\Block\Index\Index::class)
                            ->setTemplate('Ict_Quickorder::product_suggetion.phtml')
                            ->setData('products', $productCollectionSuggetion->getData())
                            ->toHtml();
                
                        $result->setData(['suggetion' => $block]);
                        return $result;
                    } else {
                        $this->messageManager->addError(__('Product not available with this result'));
                        return false;
                    }
                } else {
                    if ($productCollection->getFirstItem()->getTypeId() == 'configurable') {
                        $block = $resultPage->getLayout()
                            ->createBlock(\Ict\Quickorder\Block\Index\Index::class)
                            ->setTemplate('Ict_Quickorder::product_option.phtml')
                            ->setData('products', $productCollection)
                            ->toHtml();
                    
                        $result->setData(['products' => $block]);
                        return $result;
                    } else {
                        $result->setData(['simpleproducts' => $productCollection->getFirstItem()->getId()]);
                        return $result;
                    }
                }
            } catch (\Exception $e) {
                $this->messageManager->addError(__($e->getMessage()));
                return false;
            }
        } else {
            $this->messageManager->addError(__('Please add product Name/Sku'));
            return false;
        }
    }
}
