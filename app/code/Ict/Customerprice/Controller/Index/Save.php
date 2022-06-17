<?php

/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_Customerprice
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */

namespace Ict\Customerprice\Controller\Index;

use Magento\Framework\Controller\ResultFactory;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Area;

class Save extends \Magento\Framework\App\Action\Action
{
    public const SUCCESSMSG = "ict_customerprice/general/successmsg";
    public const ERRORMSG = "ict_customerprice/general/errormsg";
    public const QUOTE_TEMPLATE = 'ict_customerprice/general/quotetemplate';
    public const EMAIL = 'trans_email/ident_support/email';
    public const NAME = 'trans_email/ident_support/name';
    public const RECIPIENT_EMAIL = 'ict_customerprice/general/recipientemail';

    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @var \Ict\Customerprice\Model\CustomerpriceFactory
     */
    protected $customerpriceFactory;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $config;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Ict\Customerprice\Model\CustomerpriceFactory $customerpriceFactory
     * @param \Magento\Framework\App\ResourceConnection $resourceConnection
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Ict\Customerprice\Model\CustomerpriceFactory $customerpriceFactory,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->customerpriceFactory = $customerpriceFactory;
        $this->resourceConnection = $resourceConnection;
        $this->config = $config;
        $this->storeManager = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->transportBuilder = $transportBuilder;
        parent::__construct($context);
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $successmsg = $this->getConfigData(self::SUCCESSMSG);
        $errormsg = $this->getConfigData(self::ERRORMSG);
        $data = $this->getRequest()->getParams();
        $model = $this->customerpriceFactory->create();
        $model->setData($data);
        try {
            $emailTemplate = $this->getConfigData(self::QUOTE_TEMPLATE);

            if ($emailTemplate == 0) {
                $identifier = 'contact_quote_template';
            } else {
                $identifier = $emailTemplate;
            }

            $fromEmail = $this->getConfigData(self::EMAIL);
            $fromName = $this->getConfigData(self::NAME);
            $toEmail = $this->getConfigData(self::RECIPIENT_EMAIL);
            
            $templateVars = [
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'email' => $data['email'],
                'telephone' => $data['telephone'],
                'zipcode' => $data['zipcode'],
                'message' => $data['message']
            ];

            $storeId = $this->storeManager->getStore()->getId();
            $from = ['email' => $fromEmail, 'name' => $fromName];
            $this->inlineTranslation->suspend();

            $storeScope = ScopeInterface::SCOPE_STORE;
            $templateOptions = [
                'area' => Area::AREA_FRONTEND,
                'store' => $storeId
            ];
            $transport = $this->transportBuilder
                ->setTemplateIdentifier(
                    $identifier,
                    $storeScope
                )
                ->setTemplateOptions($templateOptions)
                ->setTemplateVars(['data' => $templateVars])
                ->setFrom($from)
                ->addTo($toEmail)
                ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
            
            $model->save();
            $this->messageManager->addSuccess(__($successmsg));
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addException(
                $e,
                __('Something went wrong:Can not send email.')
            );
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
        } catch (\Exception $e) {
            $this->messageManager->addError(__($errormsg));
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
        }
    }

    /**
     * @return Scope Config Value | string
     */
    public function getConfigData($path)
    {
        $value = $this->config->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getStoreId()
        );
        return $value;
    }
}
