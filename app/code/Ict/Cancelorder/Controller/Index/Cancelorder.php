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

class Cancelorder extends \Magento\Framework\App\Action\Action
{
    public const SENDER_EMAIL = 'trans_email/ident_support/email';
    public const SENDER_NAME = 'trans_email/ident_support/name';
    public const RECIPIENT_EMAIL = 'cancelorder/general/recipient';
    public const CANCELORDER_TEMPLATE = 'cancelorder/general/cancelordertemplate';

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     */
    private $transportBuilder;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    private $scopeConfig;

    /**
     * @var \Magento\Sales\Model\Order $order
     */
    private $order;

    /**
     * @var \Magento\Customer\Model\SessionFactory $customerSession
     */
    private $customerSession;
    
    /**
     * @var \Magento\Framework\DataObject $dataObject
     */
    private $dataObject;
    
    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Sales\Model\Order $order
     * @param \Magento\Customer\Model\SessionFactory $customerSession
     * @param \Magento\Framework\DataObject $dataObject
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Sales\Model\Order $order,
        \Magento\Customer\Model\SessionFactory $customerSession,
        \Magento\Framework\DataObject $dataObject
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->scopeConfig = $scopeConfig;
        $this->order = $order;
        $this->customerSession = $customerSession;
        $this->dataObject = $dataObject;
        return parent::__construct($context);
    }
    
    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        try {
            $supportEmail = $this->scopeConfig->getValue(
                self::SENDER_EMAIL,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
            $supportName = $this->scopeConfig->getValue(
                self::SENDER_NAME,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
            $recipientEmail = $this->scopeConfig->getValue(
                self::RECIPIENT_EMAIL,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
            $emailTemplate = $this->scopeConfig->getValue(
                self::CANCELORDER_TEMPLATE,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );

            if ($emailTemplate == 0) {
                $identifier = "cancel_order_template";
            } else {
                $identifier = $emailTemplate;
            }

            if ($recipientEmail === null) {
                $recipientEmail = $supportEmail;
            }

            $orderId = $this->getRequest()->getParam('orderid');
            $comment = $this->getRequest()->getParam('comment');
            $referer = $this->getRequest()->getParam('referer');

            $order = $this->order->load($orderId);
            $customer = $this->customerSession->create()->getCustomer();
            $orderIncrementId = $order->getIncrementId();
            if ($order->canCancel()) {
                $requestData = [
                    'orderid' => $orderIncrementId,
                    'customername' => $customer->getName(),
                    'comment' => $comment
                ];

                $postObject = $this->dataObject;
                $postObject->setData($requestData);

                $transport = $this->transportBuilder
                    ->setTemplateIdentifier($identifier)
                    ->setTemplateOptions(
                        [
                            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                            'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID
                        ]
                    )
                    ->setTemplateVars(['data' => $postObject])
                    ->setFrom(['name' => $supportName, 'email' => $supportEmail])
                    ->addTo([$recipientEmail])
                    ->getTransport();
                $transport->sendMessage();

                $order->cancel();
                $order->save();

                $this->messageManager->addSuccess(__('Order has been canceled successfully.'));
            } else {
                $this->messageManager->addError(__('Order cannot be canceled.'));
            }
            $resultRedirect->setUrl($referer);
            return $resultRedirect;
        } catch (\Exception $e) {
            $this->messageManager->addError(__('Something went wrong. Please try again later.'));
            $resultRedirect->setUrl($referer);
            return $resultRedirect;
        }
    }
}
