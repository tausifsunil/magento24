<?php

/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_Cancelorder
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */

namespace Ict\Cancelorder\Controller\Index;

use Magento\Framework\Controller\ResultFactory;

class Orderdetails extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    private $resultJsonFactory;
    
    /**
     * @var \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    private $resultPageFactory;
    
    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultPageFactory = $resultPageFactory;
        return parent::__construct($context);
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        $resultPage = $this->resultPageFactory->create();

        $post = $this->getRequest()->getParam('orderid');

        $block = $resultPage->getLayout()
            ->createBlock(\Ict\Cancelorder\Block\Cancel::class)
            ->setTemplate('Ict_Cancelorder::order/cancelorder.phtml')
            ->setData('orderdata', $post)
            ->toHtml();
    
        $result->setData(['order' => $block]);
        return $result;
    }
}
