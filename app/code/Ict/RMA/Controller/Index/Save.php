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
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Area;

class Save extends \Magento\Framework\App\Action\Action
{
    /**
     * Rma Customer Email Template
     */
    public const CUSTOMER_EMAIL_TEMPLATE = 'rma_templates_user';

    /**
     * Rma Admin Email Template
     */
    public const ADMIN_EMAIL_TEMPLATE = 'rma_templates';

    /**
     * General Email
     */
    public const GENERAL_EMAIL = 'trans_email/ident_general/email';

    /**
     * General Email Name
     */
    public const GENERAL_EMAIL_NAME = 'trans_email/ident_general/name';

    /**
     * @var \Ict\RMA\Model\Rma
     */
    protected $rma;

    /**
     * @var \Ict\RMA\Model\RmaMessage
     */
    protected $rmaMessage;

    /**
     * @var \Ict\RMA\Model\RmaPackageCondition
     */
    protected $rmapackage;

    /**
     * @var \Ict\RMA\Model\RmaReason
     */
    protected $rmareason;

    /**
     * @var \Ict\RMA\Model\RmaStatus
     */
    protected $rmastatus;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $order;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $fileSystem;

    /**
     * @var \Magento\Customer\Model\Customer
     */
    protected $customer;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
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
     * @param Ict\RMA\Model\RmaPackageCondition $rmapackage
     * @param Ict\RMA\Model\RmaReason $rmareason
     * @param Ict\RMA\Model\RmaStatus $rmastatus
     * @param Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param Magento\Sales\Model\Order $order
     * @param Magento\Framework\Filesystem $fileSystem
     * @param Magento\Customer\Model\Customer $customer
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
        \Ict\RMA\Model\RmaPackageCondition $rmapackage,
        \Ict\RMA\Model\RmaReason $rmareason,
        \Ict\RMA\Model\RmaStatus $rmastatus,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Sales\Model\Order $order,
        \Magento\Framework\Filesystem $fileSystem,
        \Magento\Customer\Model\Customer $customer,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\App\Response\RedirectInterface $redirect,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->rma = $rma;
        $this->rmaMessage = $rmaMessage;
        $this->rmapackage = $rmapackage;
        $this->rmareason = $rmareason;
        $this->rmastatus = $rmastatus;
        $this->date = $date;
        $this->order = $order;
        $this->filesystem = $fileSystem;
        $this->customer = $customer;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->inlineTranslation = $inlineTranslation;
        $this->transportBuilder = $transportBuilder;
        $this->redirect = $redirect;
        parent::__construct($context);
    }

    /**
     * Execute Data
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $result = [];
        $data = $this->getRequest()->getParams();
        $files = $this->getRequest()->getFiles();
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if ($data && !empty($data['product_id'])) {
            $product_Ids = count($data['product_id']);
            $model = $this->rma;
            $orders = $this->order->loadByIncrementId($data['order_id']);
            if (count($orders->getData()) > 0) {
                $customer = $this->customer->setWebsiteId(1)->loadByEmail($data['customer_email']);
                if (count($customer->getData()) > 0) {
                    if ($files['file_upload']['name']) {
                        try {
                            $uploader = $this->_objectManager->create(
                                \Magento\MediaStorage\Model\File\Uploader::class,
                                ['fileId' => 'file_upload']
                            );
                            $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                            $uploader->setAllowRenameFiles(true);
                            $uploader->setFilesDispersion(true);
                            $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
                            $result = $uploader->save($mediaDirectory->getAbsolutePath('RMA'));
                            $model->setFileUploads($result['file']);
                        } catch (Exception $e) {
                            \Zend_Debug::dump($e->getMessage());
                        }
                    }
                    $reasons = array_values(array_filter($data['reason']));
                    $data['reason'] = implode(',', $reasons);
                    $data['product_id'] = implode(',', $data['product_id']);
                    $model->setCustomerEmail($data['customer_email']);
                    $model->setOrderId($data['order_id']);
                    $model->setResolution($data['resolution']);
                    $model->setPackageCondition($data['package_condition']);
                    $model->setProductId($data['product_id']);
                    $model->setReason($data['reason']);
                    
                    if (array_key_exists('bankname', $data)
                        && array_key_exists('account_no', $data)
                        && array_key_exists('branch', $data)
                        && array_key_exists('account_name', $data)
                        && array_key_exists('account_type', $data)
                    ) {
                        $model->setBankName($data['bankname']);
                        $model->setAccountNo($data['account_no']);
                        $model->setBranch($data['branch']);
                        $model->setAccountName($data['account_name']);
                        $model->setAccountType($data['account_type']);
                    }
                    $model->setAdditionalInfo(trim($data['additional_info']));
                    $model->setStatus(1);
                    $model->setCreatedAt($this->date->gmtDate());
                    $model->setUpdatedAt($this->date->gmtDate());
                    if ($data['resolution'] == 1) {
                        $resolution = 'Refund';
                    } elseif ($data['resolution'] == 2) {
                        $resolution = 'Replacement';
                    }
                    $progrid = '';
                    for ($i = 0; $i < $product_Ids; $i++) {
                        $progrid .= " <div class='products-content'>
                                        <div class='pro-image'><img src='" . $data['productimage'][$i] . "'></div>
                                        <div class='pro-name'>" . $data['productname'][$i] . "</div>
                                        <div class='pro-sku'>" . $data['productsku'][$i] . "</div>
                                        <div class='reason'>Reason : "
                                        . $this->rmareason->load($data['reason'][$i])->getName() . "</div>
                                    </div>
                                ";
                    }
                    $generalEmail = $this->scopeConfig->getValue(
                        self::GENERAL_EMAIL,
                        ScopeInterface::SCOPE_STORE
                    );
                    $generalName = $this->scopeConfig->getValue(
                        self::GENERAL_EMAIL_NAME,
                        ScopeInterface::SCOPE_STORE
                    );
                    $image = '';
                    if (isset($result['file']) && $result['file']) {
                        $image = $this->storeManager->getStore()->getBaseUrl(
                            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                        ) . 'RMA' . $result['file'];
                    }
                    try {
                        /* sending email to customer */
                        $fromEmail = $generalEmail;
                        $fromName = $generalName;
                        $toEmail = $data['customer_email'];
                        $templateVars = [
                            'store' => $this->storeManager->getStore(),
                            'customer_name' => $customer->getFirstname() . ' ' . $customer->getLastname(),
                            'date' => $model->getData('created_at'),
                            'order_id' => $data['order_id'],
                            'rma_id' => $model->getData('rma_id'),
                            'resolution' => $resolution,
                            'package_condition' => $this->rmapackage->load($data['package_condition'])->getName(),
                            'products' => $progrid,
                            'image' => $image,
                            'status' => $this->rmastatus->load(1)->getStatuses(),
                            'additional_info' => trim($data['additional_info'])
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
                                self::CUSTOMER_EMAIL_TEMPLATE,
                                $storeScope
                            )
                            ->setTemplateOptions($templateOptions)
                            ->setTemplateVars($templateVars)
                            ->setFrom($from)
                            ->addTo($toEmail)
                            ->getTransport();
                        $transport->sendMessage();
                        $this->inlineTranslation->resume();
                        /* Send email to admin user */
                        $toEmailAdmin = $generalEmail;
                        $templateVarsAdmin = [
                            'store' => $this->storeManager->getStore(),
                            'customer_name' => $generalName,
                            'customer_email' => $data['customer_email'],
                            'order_id' => $data['order_id'],
                            'rma_id' => $model->getData('rma_id'),
                            'resolution' => $resolution,
                            'package_condition' => $this->rmapackage->load($data['package_condition'])->getName(),
                            'products' => $progrid,
                            'image' => $image,
                            'status' => $this->rmastatus->load(1)->getStatuses(),
                            'additional_info' => trim($data['additional_info'])
                        ];
                        $this->inlineTranslation->suspend();
                        $transportAdmin = $this->transportBuilder
                            ->setTemplateIdentifier(self::ADMIN_EMAIL_TEMPLATE, $storeScope)
                            ->setTemplateOptions($templateOptions)
                            ->setTemplateVars($templateVarsAdmin)
                            ->setFrom($from)
                            ->addTo($toEmailAdmin)
                            ->getTransport();
                        $transportAdmin->sendMessage();
                        $this->inlineTranslation->resume();

                        $model->save();
                        $model_message = $this->rmaMessage;
                        $model_message->setRmaId($model->getData('rma_id'));
                        $model_message->setMessage(trim($data['additional_info']));
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
                } else {
                    $this->messageManager->addError(__('Your email address is not valid!'));
                    $resultRedirect->setUrl($this->redirect->getRefererUrl());
                    return $resultRedirect;
                }
            } else {
                $this->messageManager->addError(__('Your order detail is not valid!'));
                $resultRedirect->setUrl($this->redirect->getRefererUrl());
                return $resultRedirect;
            }
        } else {
            $this->messageManager->addError(
                __('Your RMA request has beed decline. Please select minimum one product to generate RMA')
            );
            $resultRedirect->setUrl($this->redirect->getRefererUrl());
            return $resultRedirect;
        }
        $resultRedirect->setUrl($this->redirect->getRefererUrl());
        return $resultRedirect;
    }
}
