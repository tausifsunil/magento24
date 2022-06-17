<?php

/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_RMA
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */

namespace Ict\RMA\Block\Index;

use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;

class Index extends \Magento\Framework\View\Element\Template
{
    /**
     * Request Parameter
     */
    public const REQUEST_PARAMETER = 'rmaid';
    
    /**
     * Customer Id Columnname
     */
    public const CUSTOMER_ID_COLUMNNAME = 'customer_id';
    
    /**
     * Order Increment Id
     */
    public const ORDER_INCREMENTID_COLUMN = 'increment_id';
    
    /**
     * Order Id
     */
    public const ORDER_ID_COLUMN = 'order_id';
    
    /**
     * Order Status
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
     * Rma Id
     */
    public const RMA_ID = 'rma_id';
    
    /**
     * Refund Label
     */
    public const REFUND_LABEL = 'Refund';

    /**
     * Replacement
     */
    public const REPLACEMENT_LABEL = 'Replacement';

    /**
     * Config Value Path
     */
    public const IS_ENABLE_BANK_DETAIL = 'ict_rma/rma/bank_detail_enabled';

    /**
     * @var \Ict\RMA\Model\RmaPackageConditionFactory
     */
    protected $rmapackageFactory;

    /**
     * @var \Ict\RMA\Model\RmaPackageCondition
     */
    protected $rmapackage;

    /**
     * @var \Ict\RMA\Model\RmaFactory
     */
    protected $rmaFactory;

    /**
     * @var \Ict\RMA\Model\Rma
     */
    protected $rma;

    /**
     * @var \Ict\RMA\Model\RmaReason
     */
    protected $rmareason;

    /**
     * @var \Ict\RMA\Model\RmaStatus
     */
    protected $rmastatus;

    /**
     * @var \Ict\RMA\Model\RmaMessageFactory
     */
    protected $rmamessageFactory;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepo;

    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $order;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $orderCollectionFactory;

    /**
     * @var \Magento\Customer\Model\SessionFactory
     */
    protected $customerSession;

    /**
     * @var \Magento\Catalog\Helper\ImageFactory
     */
    protected $imageHelperFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param Ict\RMA\Model\RmaPackageConditionFactory $rmapackageFactory
     * @param Ict\RMA\Model\RmaPackageCondition $rmapackage
     * @param Ict\RMA\Model\RmaFactory $rmaFactory
     * @param Ict\RMA\Model\Rma $rma
     * @param Ict\RMA\Model\RmaReason $rmareason
     * @param Ict\RMA\Model\RmaStatus $rmastatus
     * @param Ict\RMA\Model\RmaMessageFactory $rmamessageFactory
     * @param Magento\Catalog\Api\ProductRepositoryInterface $productRepo
     * @param Magento\Sales\Model\Order $order
     * @param Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
     * @param Magento\Customer\Model\SessionFactory $customerSession
     * @param Magento\Catalog\Helper\ImageFactory $imageHelperFactory
     * @param Magento\Store\Model\StoreManagerInterface $storeManager
     * @param Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param Magento\Catalog\Block\Product\Context $context
     * @param array $data
     */
    public function __construct(
        \Ict\RMA\Model\RmaPackageConditionFactory $rmapackageFactory,
        \Ict\RMA\Model\RmaPackageCondition $rmapackage,
        \Ict\RMA\Model\RmaFactory $rmaFactory,
        \Ict\RMA\Model\Rma $rma,
        \Ict\RMA\Model\RmaReason $rmareason,
        \Ict\RMA\Model\RmaStatus $rmastatus,
        \Ict\RMA\Model\RmaMessageFactory $rmamessageFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepo,
        \Magento\Sales\Model\Order $order,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Customer\Model\SessionFactory $customerSession,
        \Magento\Catalog\Helper\ImageFactory $imageHelperFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Catalog\Block\Product\Context $context,
        array $data = []
    ) {
        $this->rmapackageFactory = $rmapackageFactory;
        $this->rmapackage = $rmapackage;
        $this->rmaFactory = $rmaFactory;
        $this->rma = $rma;
        $this->rmareason = $rmareason;
        $this->rmastatus = $rmastatus;
        $this->rmamessageFactory = $rmamessageFactory;
        $this->productRepo = $productRepo;
        $this->order = $order;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->customerSession = $customerSession;
        $this->imageHelperFactory = $imageHelperFactory;
        $this->storeManager = $storeManager;
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
        $cust_id = $this->customerSession->create()->getCustomer()->getId();
        $collection = $this->orderCollectionFactory->create()
            ->addFieldToFilter(self::CUSTOMER_ID_COLUMNNAME, $cust_id);
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
     * Get Package Condition Collectioon
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
        return $this->customerSession->create()->getCustomer()->getEmail();
    }

    /**
     * Get Customer Name
     *
     * @return Customer Name
     */
    public function getCustomerName()
    {
        $customer = $this->customerSession->create()->getCustomer();
        return $customer->getFirstname() . ' ' . $customer->getLastname();
    }

    /**
     * Get Customer Id
     *
     * @return Customer Id
     */
    public function getCustomerId()
    {
        return $this->customerSession->create()->getCustomer()->getId();
    }

    /**
     * @return Order - Product Collection
     */
    public function getproducts($id, $reasonid)
    {
        $ids = [];
        $reason_id = [];
        $ids = explode(",", $id);
        $reason_id = explode(",", $reasonid);
        $html = '';
        $i = 0;

        foreach ($ids as $pid) {
            $rmareason = $this->rmareason->load($reason_id[$i]);
            $_product = $this->productRepo->getById($pid);
            $imageUrl = $this->imageHelperFactory->create()
                ->init($_product, self::PRODUCT_IMAGE_TYPE)
                ->resize(self::IMAGE_RESIZE)
                ->getUrl();
            $html .= "
                <div class='product-count productdata_" . $_product->getId() . "'>
                    <div class='pro_img'><img src='" . $imageUrl . "'></div>
                    <div class='pro-content'><span class='pro_name'>" . $_product->getName() . "</span>
                    <span class='pro_sku'>" . $_product->getSku() . "</span>
                    <span class='reason'>Reason: </span><span class='reason-name'>" . $rmareason->getName() . "</span>
                    </div>
                </div>
            ";
            $i++;
        }
        return $html;
    }

    /**
     * Get Rma Product Image
     *
     * @param numeric $id
     * @return RMA Image
     */
    public function getRmaProductImage($id)
    {
        try {
            $productId = (int)$id;
            $product = $this->productRepo->getById($productId);
            $imageUrl = $this->imageHelperFactory->create()
                ->init($product, self::PRODUCT_IMAGE_TYPE)
                ->resize(self::IMAGE_RESIZE)
                ->getUrl();
            return $imageUrl;
        } catch (\Exception $e) {
            return __($e->getMessage());
        }
    }

    /**
     * Get Message Collection
     *
     * @param numeric $rmaid
     * @return Message Collection
     */
    public function getMessageCollection($rmaid)
    {
        return $this->rmamessageFactory->create()
            ->getCollection()
            ->addFieldToFilter(self::RMA_ID, $rmaid);
    }

    /**
     * Get Resolution
     *
     * @param numeric $resolution
     * @return Resolution
     */
    public function getResolution($resolution)
    {
        if ($resolution == 1) {
            return __('Refund');
        } elseif ($resolution == 2) {
            return __('Replacement');
        }
    }

    /**
     * Get Package Conditions
     *
     * @param numeric $packageid
     * @return PackageCondition Collection
     */
    public function getPackageConditions($packageid)
    {
        $_collection = $this->rmapackage->load($packageid);
        return $_collection->getName();
    }

    /**
     * Get Statuses
     *
     * @param numeric $statusid
     * @return Status Collection
     */
    public function getStatuses($statusid)
    {
        $_collection = $this->rmastatus->load($statusid);
        return $_collection->getStatuses();
    }

    /**
     * Get File Uploads
     *
     * @param string $file
     * @return Image
     */
    public function getFileUploads($file)
    {
        return $this->storeManager->getStore()
            ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . 'RMA' . $file;
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
