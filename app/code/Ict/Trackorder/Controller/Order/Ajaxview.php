<?php

/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_Trackorder
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */

namespace Ict\Trackorder\Controller\Order;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Result\PageFactory;

class Ajaxview extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    private $resultPageFactory;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    private $resultJsonFactory;
    
    /**
     * @var \Magento\Sales\Model\Order $order
     */
    private $order;
    
    /**
     * @var \Magento\Framework\Registry $registry
     */
    private $registry;
    
    /**
     * @var \Magento\Framework\View\LayoutInterface $layout
     */
    private $layout;
    
    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Sales\Model\Order $order
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\View\LayoutInterface $layout
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Sales\Model\Order $order,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\View\LayoutInterface $layout,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->order = $order;
        $this->registry = $registry;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->layout = $layout;
        $this->resultPageFactory = $resultPageFactory;
    }
    
    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        $order_id = $this->getRequest()->getPostValue('order_id');
        $email_id = $this->getRequest()->getPostValue('email_id');
        $order_data = $this->order->loadByIncrementId($order_id);
        
        try {
            if (!empty($order_data->getData())) {
                $customer_email = $order_data->getCustomerEmail();
                if ($email_id == $customer_email) {
                    $this->registry->register('current_order', $order_data);
                    $resultPage = $this->resultPageFactory->create();
                    $order_items = $this->layout->getBlock('sales.order.items')->toHtml();
                    $order_info = $this->layout->getBlock('sales.order.info')->toHtml();
                    $order_status = $this->layout->getBlock('order_status')->toHtml();
                    $result->setData(
                        [
                          'status' => 'success',
                          'order_status' => $order_status,
                          'order_items' => $order_items,
                          'order_info' => $order_info
                        ]
                    );
                    $this->messageManager->addSuccess(__('An Order is found with given OrderId.'));
                    return $result;
                } else {
                    $this->messageManager
                    ->addError(__('EmailID or OrderID is not matching, Please check your EmailID or OrderID.'));
                    return $result->setData(['status' => 'errror']);
                }
            } else {
                $this->messageManager
                ->addError(__('There is no any order exist with this OrderID, Please enter proper OrderID.'));
                return $result->setData(['status' => 'errror']);
            }
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
    }
}
