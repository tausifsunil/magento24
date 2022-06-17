<?php

/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_RMA
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */

namespace Ict\RMA\Controller\Index;

use Magento\Framework\Controller\ResultFactory;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Area;

class Edit extends \Magento\Framework\App\Action\Action
{
    /**
     * Rma Id
     */
    public const RMA_ID = 'rmaid';

    /**
     * Customer Id
     */
    public const CUSTOMER_ID = 'customer_id';

    /**
     * Trans General Email
     */
    public const GENERAL_EMAIL = 'trans_email/ident_general/email';

    /**
     * Trans General Email Name
     */
    public const GENERAL_EMAIL_NAME = 'trans_email/ident_general/name';

    /**
     * Admin Email Template Id
     */
    public const ADMIN_EMAIL_TEMPLATE_ID = 'rma_edit_templates_admin';

    /**
     * Customer Email Template Id
     */
    public const CUSTOMER_EMAIL_TEMPLATE_ID = 'rma_edit_templates';

    /**
     * @var \Ict\RMA\Model\Rma
     */
    protected $rma;

    /**
     * @var \Ict\RMA\Model\RmaMessage
     */
    protected $rmaMessage;

    /**
     * @var \Magento\Customer\Model\Customer
     */
    protected $customer;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    private $inlineTranslation;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var Magento\Framework\App\Response\RedirectInterface
     */
    protected $redirect;

    /**
     * @param Ict\RMA\Model\Rma $rma
     * @param Ict\RMA\Model\RmaMessage $rmaMessage
     * @param Magento\Customer\Model\Customer $customer
     * @param Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param Magento\Store\Model\StoreManagerInterface $storeManager
     * @param Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param Magento\Framework\App\Response\RedirectInterface $redirect
     * @param Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Ict\RMA\Model\Rma $rma,
        \Ict\RMA\Model\RmaMessage $rmaMessage,
        \Magento\Customer\Model\Customer $customer,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\App\Response\RedirectInterface $redirect,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->rma = $rma;
        $this->rmaMessage = $rmaMessage;
        $this->customer = $customer;
        $this->date = $date;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->inlineTranslation = $inlineTranslation;
        $this->transportBuilder = $transportBuilder;
        $this->redirect = $redirect;
        parent::__construct($context);
    }

    /**
     * Rma Edit Page
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $data = $this->getRequest()->getParams();
        $order_id = $this->rma->load($data[self::RMA_ID])->getOrderId();
        $customer = $this->customer->load($data[self::CUSTOMER_ID]);
        if ($data) {
            try {
                $admin_email = $this->scopeConfig->getValue(
                    self::GENERAL_EMAIL,
                    ScopeInterface::SCOPE_STORE
                );
                $admin_name = $this->scopeConfig->getValue(
                    self::GENERAL_EMAIL_NAME,
                    ScopeInterface::SCOPE_STORE
                );

                /* sending email to admin */
                $fromEmail = $admin_email;
                $fromName = $admin_name;
                $storeId = $this->storeManager->getStore()->getId();
                $templateOptions = [
                    'area' => Area::AREA_FRONTEND,
                    'store' => $storeId
                ];
                $templateVars = [
                    'store' => $this->storeManager->getStore(),
                    'customer_name' => $admin_name,
                    'customer_email' => $data['customer_email'],
                    'rma_id' => $data['rmaid'],
                    'order_id' => $order_id,
                    'additional_info' => $data['additional_info']
                ];
                $from = ['email' => $fromEmail, 'name' => $fromName];
                $this->inlineTranslation->suspend();
                $storeScope = ScopeInterface::SCOPE_STORE;

                $transport = $this->transportBuilder->setTemplateIdentifier(self::ADMIN_EMAIL_TEMPLATE_ID, $storeScope)
                    ->setTemplateOptions($templateOptions)
                    ->setTemplateVars($templateVars)
                    ->setFrom($from)
                    ->addTo($admin_email)
                    ->getTransport();
                $transport->sendMessage();
                $this->inlineTranslation->resume();

                /* sending email to Customer */
                $to = $data['customer_email'];
                $customerName = $customer->getFirstname() . ' ' . $customer->getLastname();
                $templateVarsUser = [
                    'store' => $this->storeManager->getStore(),
                    'customer_name' => $customerName,
                    'rma_id' => $data['rmaid'],
                    'order_id' => $order_id,
                    'additional_info' => $data['additional_info']
                ];
                $this->inlineTranslation->suspend();
                $transportUser = $this->transportBuilder
                    ->setTemplateIdentifier(
                        self::CUSTOMER_EMAIL_TEMPLATE_ID,
                        $storeScope
                    )
                    ->setTemplateOptions($templateOptions)
                    ->setTemplateVars($templateVarsUser)
                    ->setFrom($from)
                    ->addTo($to)
                    ->getTransport();
                $transportUser->sendMessage();
                $this->inlineTranslation->resume();

                $model_message = $this->rmaMessage;
                $model_message->setRmaId($data['rmaid']);
                $model_message->setMessage($data['additional_info']);
                $model_message->setType("customer");
                $model_message->setCreatedAt($this->date->gmtDate());
                $model_message->save();
                $this->messageManager->addSuccess(__('The Rma has been saved.'));

                $resultRedirect->setUrl($this->redirect->getRefererUrl());
                return $resultRedirect;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addException(
                    $e,
                    __('Something went wrong while saving the Rma:Can not send email.')
                );
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException(
                    $e,
                    __('Something went wrong while saving the Rma.')
                );
            }
        }
        $resultRedirect->setUrl($this->redirect->getRefererUrl());
        return $resultRedirect;
    }
}
