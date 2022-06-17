<?php

namespace Forever\AuthenticationPopUp\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Forever\AuthenticationPopUp\Helper\Data as AjaxLoginHelper;

class Addtowishlist extends Action
{

    /**
     * @var Magento\Customer\Model\Session
     * */
    protected $customerSession;

    /**
     * @var Magento\Wishlist\Model\WishlistFactory
     * */
    protected $wishlistRepository;

    /**
     * @var Magento\Catalog\Api\ProductRepositoryInterface
     * */
    protected $productRepository;
    protected $helper;

    /**
     * @param Magento\Customer\Model\Session $customerSession
     * @param Magento\Wishlist\Model\WishlistFactory $wishlistRepository
     * @param Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param Magento\Framework\Controller\ResultFactory $resultFactory
     * @param Magento\Framework\Controller\Result\JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Wishlist\Model\WishlistFactory $wishlistRepository,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        ResultFactory $resultFactory,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        AjaxLoginHelper $helper
    ) {
        $this->customerSession = $customerSession;
        $this->wishlistRepository = $wishlistRepository;
        $this->productRepository = $productRepository;
        $this->resultFactory = $resultFactory;
        $this->jsonFactory = $jsonFactory;
        $this->helper = $helper;
        parent::__construct($context);
    }

    /**
     * Display Status, Redirect Page and show Message.
     * @return mixed
     * */
    public function execute()
    {   
        $customerId = $this->customerSession->getCustomer()->getId();
        if (!$customerId) {
            $jsonData = ['status' => 400, 'redirect' => 0,'message' => 'Customer not logged in.'];
            $result = $this->jsonFactory->create()->setData($jsonData);
            return $result;
        }
        $productId = $this->getRequest()->getParam('productId');
        try {
            $product = $this->productRepository->getById($productId);
        } catch (NoSuchEntityException $e) {
            $product = null;
        }
        $wishlist = $this->wishlistRepository->create()->loadByCustomerId($customerId, true);
        $wishlist->addNewItem($product);
        $wishlist->save();

        $jsonData = ['status' => 200, 'redirect' => 1, 'message' => 'Added to wishlist'];
        $result = $this->jsonFactory->create()->setData($jsonData);
        return $result;
    }
}
