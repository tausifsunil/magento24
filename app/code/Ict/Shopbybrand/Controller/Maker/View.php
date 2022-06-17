<?php
/**
 * @author Ict Team
 * @copyright Copyright (c) 2017 Ict (http://icreativetechnologies.com/)
 * @package Ict_Shopbybrand
 */

namespace Ict\Shopbybrand\Controller\Maker;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Ict\Shopbybrand\Model\MakerFactory;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;
use Ict\Shopbybrand\Model\Maker\Url as UrlModel;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Store\Model\ScopeInterface;

class View extends Action
{
    const BREADCRUMBS_CONFIG_PATH = 'ict_shopbybrand/maker/breadcrumbs';
 
    const ENABLED_CONFIG_PATH = 'ict_shopbybrand/maker/enabled';
 
    protected $makerFactory;

    protected $resultForwardFactory;

    protected $resultPageFactory;

    protected $coreRegistry;

    protected $urlModel;

    protected $scopeConfig;

    public function __construct(
        Context $context,
        MakerFactory $makerFactory,
        ForwardFactory $resultForwardFactory,
        PageFactory $resultPageFactory,
        Registry $coreRegistry,
        UrlModel $urlModel,
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->resultForwardFactory = $resultForwardFactory;
        $this->makerFactory = $makerFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $coreRegistry;
        $this->urlModel = $urlModel;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\Result\Forward|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        if($this->scopeConfig->isSetFlag(self::ENABLED_CONFIG_PATH, ScopeInterface::SCOPE_STORE)==1){

            $makerId = (int) $this->getRequest()->getParam('id');
            $maker = $this->makerFactory->create();
            $maker->load($makerId);

            if (!$maker->isActive()) {
                $resultForward = $this->resultForwardFactory->create();
                $resultForward->forward('noroute');
                return $resultForward;
            }

            $this->coreRegistry->register('current_maker', $maker);

            $resultPage = $this->resultPageFactory->create();
            $title = ($maker->getMetaTitle()) ?: $maker->getName();
            $resultPage->getConfig()->getTitle()->set($title);
            $resultPage->getConfig()->setDescription($maker->getMetaDescription());
            $resultPage->getConfig()->setKeywords($maker->getMetaKeywords());
            if ($this->scopeConfig->isSetFlag(self::BREADCRUMBS_CONFIG_PATH, ScopeInterface::SCOPE_STORE)) {
                /** @var \Magento\Theme\Block\Html\Breadcrumbs $breadcrumbsBlock */
                $breadcrumbsBlock = $resultPage->getLayout()->getBlock('breadcrumbs');
                if ($breadcrumbsBlock) {
                    $breadcrumbsBlock->addCrumb(
                        'home',
                        [
                            'label' => __('Home'),
                            'link'  => $this->_url->getUrl('')
                        ]
                    );
                    $breadcrumbsBlock->addCrumb(
                        'makers',
                        [
                            'label' => __('Shopbybrand'),
                            'link'  => $this->urlModel->getListUrl()
                        ]
                    );
                    $breadcrumbsBlock->addCrumb(
                        'maker-'.$maker->getId(),
                        [
                            'label' => $maker->getName()
                        ]
                    );
                }
            }

            return $resultPage;
        }else{
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
        }
    }
}
