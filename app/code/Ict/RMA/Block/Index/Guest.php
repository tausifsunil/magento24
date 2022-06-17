<?php

/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_RMA
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */

namespace Ict\RMA\Block\Index;

use Magento\Customer\Model\Context as CustomerContext;
use Magento\Store\Model\ScopeInterface;

class Guest extends \Magento\Framework\View\Element\Template
{
    /**
     * Request Parameter
     */
    public const REQUEST_PARAMETER = 'rmaid';
    
    /**
     * Customer Id
     */
    public const CUSTOMER_ID_COLUMNNAME = 'customer_id';
    
    /**
     * Increment Id
     */
    public const ORDER_INCREMENTID_COLUMN = 'increment_id';
    
    /**
     * Order Id
     */
    public const ORDER_ID_COLUMN = 'order_id';
    
    /**
     * Status
     */
    public const ORDER_STATUS = 'status';
    
    /**
     * Product Id
     */
    public const PRODUCT_ID = 'product_id';
    
    /**
     * Product Image Type
     */
    public const PRODUCT_IMAGE_TYPE = 'product_thumbnail_image';
    
    /**
     * Image Resize
     */
    public const IMAGE_RESIZE = 150;

    /**
     * Config Value Path
     */
    public const IS_ENABLE_BANK_DETAIL = 'ict_rma/rma/bank_detail_enabled';

    /**
     * @var \Ict\RMA\Model\RmaPackageConditionFactory
     */
    protected $rmapackageFactory;

    /**
     * @var \Ict\RMA\Model\RmaReason
     */
    protected $rmareason;

    /**
     * @var \Ict\RMA\Model\RmaFactory
     */
    protected $rmaFactory;

    /**
     * @var \Ict\RMA\Model\Rma
     */
    protected $rma;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $product;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $order;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $orderCollectionFactory;

    /**
     * @var \Magento\Catalog\Helper\ImageFactory
     */
    protected $imageHelperFactory;

    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $authContext;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param Ict\RMA\Model\RmaPackageConditionFactory $rmapackageFactory
     * @param Ict\RMA\Model\RmaReason $rmareason
     * @param Ict\RMA\Model\RmaFactory $rmaFactory
     * @param Ict\RMA\Model\Rma $rma
     * @param Magento\Catalog\Model\ProductFactory $product
     * @param Magento\Customer\Model\Session $customerSession
     * @param Magento\Sales\Model\Order $order
     * @param Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
     * @param Magento\Catalog\Helper\ImageFactory $imageHelperFactory
     * @param Magento\Framework\App\Http\Context $authContext
     * @param Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param Magento\Catalog\Block\Product\Context $context
     * @param array $data
     * */
    public function __construct(
        \Ict\RMA\Model\RmaPackageConditionFactory $rmapackageFactory,
        \Ict\RMA\Model\RmaReason $rmareason,
        \Ict\RMA\Model\RmaFactory $rmaFactory,
        \Ict\RMA\Model\Rma $rma,
        \Magento\Catalog\Model\ProductFactory $product,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Sales\Model\Order $order,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Catalog\Helper\ImageFactory $imageHelperFactory,
        \Magento\Framework\App\Http\Context $authContext,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Catalog\Block\Product\Context $context,
        array $data = []
    ) {
        $this->rmapackageFactory = $rmapackageFactory;
        $this->rmareason = $rmareason;
        $this->rmaFactory = $rmaFactory;
        $this->rma = $rma;
        $this->customerSession = $customerSession;
        $this->order = $order;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->imageHelperFactory = $imageHelperFactory;
        $this->authContext = $authContext;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
    }

    /**
     * Get Collection
     *
     * @return RMA Collection
     */
    public function getCollection()
    {
        $id = $this->getRequest()->getParam(self::REQUEST_PARAMETER);
        return $this->rma->load($id);
    }

    /**
     * Get Order Ids
     *
     * @return Order Ids
     */
    public function getOrderIds()
    {
        $collection = $this->orderCollectionFactory->create()
            ->addFieldToFilter(self::CUSTOMER_ID_COLUMNNAME, $this->customerSession->getCustomer()->getId());
        $result = '';

        foreach ($collection as $order) {
            $_collection = $this->order->load($order->getIncrementId(), self::ORDER_INCREMENTID_COLUMN);
            $_productCollection = $_collection->getAllVisibleItems();
            $rmaCollection = $this->rmaFactory->create()->getCollection()
                ->addFieldToFilter(self::ORDER_ID_COLUMN, $order->getIncrementId())
                ->addFieldToFilter(self::ORDER_STATUS, ['in' => [1,3]]);
            if (count($rmaCollection->getData()) > 0) {
                $rmaproducts = [];
                $count = 0;
                foreach ($rmaCollection as $key) {
                    $pro_ids = explode(",", $key[self::PRODUCT_ID]);
                    array_push($rmaproducts, $pro_ids);
                    $count++;
                }

                if (count($_productCollection) != $count) {
                    $result .= "
                        <option value=" . $order->getIncrementId() . ">#" . $order->getIncrementId() . "</option>
                    ";
                }
            } else {
                $result .= "<option value=" . $order->getIncrementId() . ">#" . $order->getIncrementId() . "</option>";
            }
        }
        
        return $result;
    }

    /**
     * Get Package Condition
     *
     * @return PackageCondition Collection
     */
    public function getPackageCondition()
    {
        $packageCollection = $this->rmapackageFactory->create()->getCollection();
        if ($packageCollection->getSize()) {
            $result = '';
            foreach ($packageCollection as $_key) {
                $result .= "<option value=" . $_key->getRmaPackageConditionId() . ">" . $_key->getName() . "</option>";
            }
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Get Customer Email
     *
     * @return Customer Email
     */
    public function getCustomerEmail()
    {
        return $this->customerSession->getCustomer()->getEmail();
    }

    /**
     * Get Products
     *
     * @param numeric $id
     * @param numeric $reasonid
     * @return Order - Product Collection
     */
    public function getproducts($id, $reasonid)
    {
        $ids = explode(",", $id);
        $reason_id = explode(",", $reasonid);
        $html = '';
        $i = 0;
        foreach ($ids as $pid) {
            $rmareason = $this->rmareason->load($reason_id[$i]);
            $_product = $this->product->create()->load($pid);
            $imageUrl = $this->imageHelperFactory->create()
                ->init($_product, self::PRODUCT_IMAGE_TYPE)
                ->resize(self::IMAGE_RESIZE)
                ->getUrl();
            $html .= "
                <div class='productdata_" . $_product->getId() . "'>
                    <span class='pro_img'><img src='" . $imageUrl . "'></span>
                    <span class='pro_name'>" . $_product->getName() . "</span>
                    <span class='pro_sku'>" . $_product->getSku() . "</span>
                    <span class='pro_sku'>" . $rmareason->getName() . "</span>
                    
                </div>
            ";
            $i++;
        }
        return $html;
    }

    /**
     * Get Customer Is Logged In Or Not
     *
     * @return bool
     */
    public function getCustomerIsLoggedInOrNot()
    {
        $isLoggedIn = $this->authContext->getValue(CustomerContext::CONTEXT_AUTH);
        return (int)$isLoggedIn;
    }

    /**
     * Get Config Bank Detail Option Value
     *
     * @return bool
     */
    public function isBankDetailEnable()
    {
        return $this->scopeConfig->getValue(
            self::IS_ENABLE_BANK_DETAIL,
            ScopeInterface::SCOPE_STORE
        );
    }
}
