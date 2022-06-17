<?php

namespace Forever\LayeredNavigation\Controller\Search\Result;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Search\Model\Query;
use Magento\Catalog\Model\Layer\Resolver;

class Index extends Action
{
    const CONFIG_ENABLE = 'layered_navigation/general/ajax_enable';
    
    /**
     * Catalog session
     *
     * @var Session
     */
    protected $catalogSession;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @type JsonData
     */
    protected $jsonHelper;
    
    /**
     * @var AjaxLayerViewModel
     */
    protected $moduleViewmodel;

    /**
     * @type Data
     */
    protected $helper;

    /**
     * @var QueryFactory
     */
    private $queryFactory;

    /**
     * Catalog Layer Resolver
     *
     * @var Resolver
     */
    private $layerResolver;

    /**
     * Index constructor.
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Catalog\Model\Session $catalogSession
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Search\Model\QueryFactory $queryFactory
     * @param Resolver $layerResolver
     * @param \Magento\CatalogSearch\Helper\Data $helper
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Forever\LayeredNavigation\ViewModel\AjaxLayerViewModel $moduleViewmodel
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Catalog\Model\Session $catalogSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Search\Model\QueryFactory $queryFactory,
        Resolver $layerResolver,
        \Magento\CatalogSearch\Helper\Data $helper,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Forever\LayeredNavigation\ViewModel\AjaxLayerViewModel $moduleViewmodel
    ) {
        $this->_storeManager = $storeManager;
        $this->_catalogSession = $catalogSession;
        $this->_queryFactory = $queryFactory;
        $this->layerResolver = $layerResolver;
        $this->_jsonHelper = $jsonHelper;
        $this->moduleViewmodel = $moduleViewmodel;
        $this->_helper = $helper;

        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultInterface|void
     * @throws LocalizedException
     */
    public function execute()
    {
        $this->layerResolver->create(Resolver::CATALOG_LAYER_SEARCH);
        /* @var $query Query */
        $query = $this->_queryFactory->get();

        $query->setStoreId($this->_storeManager->getStore()->getId());

        if ($query->getQueryText() !== '') {
            if ($this->_helper->isMinQueryLength()) {
                $query->setId(0)->setIsActive(1)->setIsProcessed(1);
            } else {
                $query->saveIncrementalPopularity();
                if ($query->getRedirect()) {
                    $this->getResponse()->setRedirect($query->getRedirect());

                    return;
                }
            }

            $this->_helper->checkNotes();
            $this->_view->loadLayout();

            if ($this->moduleViewmodel->getScopeconfig(self::CONFIG_ENABLE) && $this->getRequest()->isAjax()) {
                $navigation = $this->_view->getLayout()->getBlock('catalogsearch.leftnav');
                $products = $this->_view->getLayout()->getBlock('search.result');
                $result = [
                    'products' => $products->toHtml(),
                    'navigation' => $navigation->toHtml()
                ];
                $this->getResponse()->representJson($this->_jsonHelper->jsonEncode($result));
            } else {
                $this->_view->renderLayout();
            }
        } else {
            $this->getResponse()->setRedirect($this->_redirect->getRedirectUrl());
        }
    }
}
