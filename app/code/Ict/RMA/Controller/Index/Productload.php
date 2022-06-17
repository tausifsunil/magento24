<?php

/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_RMA
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */

namespace Ict\RMA\Controller\Index;

class Productload extends \Magento\Framework\App\Action\Action
{
    public const IMAGE_TYPE = 'product_thumbnail_image';
    public const PRODUCT_ID_FIELD = 'product_id';
    public const STATUS_FIELD = 'status';
    public const PRODUCT_IMAGE_WIDTH = 200;
    public const PRODUCT_IMAGE_HEIGHT = 300;
    public const RESPONSE_TYPE = 'html';
    public const ORDER_ID_COLUMN = 'order_id';
    public const ORDER_INCREMENT_ID_COLUMN = 'increment_id';
    public const POST_VALUE = 'orderids';

    /**
     * @var \Ict\RMA\Model\RmaFactory
     */
    protected $rmaFactory;

    /**
     * @var \Ict\RMA\Model\RmaReasonFactory
     */
    protected $rmareasonFactory;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $order;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;

    /**
     * @var \Magento\Catalog\Helper\ImageFactory
     */
    protected $productImageHelper;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @param Ict\RMA\Model\RmaFactory $rmaFactory
     * @param Ict\RMA\Model\RmaReasonFactory $rmareasonFactory
     * @param Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param Magento\Sales\Model\Order $order
     * @param Magento\Catalog\Model\ProductFactory $productFactory
     * @param Magento\Catalog\Helper\ImageFactory $productImageHelper
     * @param Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param Magento\Framework\Message\ManagerInterface $messageManager
     * @param Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Ict\RMA\Model\RmaFactory $rmaFactory,
        \Ict\RMA\Model\RmaReasonFactory $rmareasonFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Sales\Model\Order $order,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Helper\ImageFactory $productImageHelper,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->rmaFactory = $rmaFactory;
        $this->rmareasonFactory = $rmareasonFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->order = $order;
        $this->productFactory = $productFactory;
        $this->productImageHelper = $productImageHelper;
        $this->scopeConfig = $scopeConfig;
        $this->messageManager = $messageManager;
        return parent::__construct($context);
    }

    /**
     * Initialize requested product particular Order object
     *
     * @return Products from particular Order
     */
    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        $orderid = $this->getRequest()->getPostValue(self::POST_VALUE);
        $_collection = $this->order->load($orderid, self::ORDER_INCREMENT_ID_COLUMN);
        $html = "<div class='all_products'><legend class='legend'><span>Products</span></legend>";

        if (count($_collection->getData()) > 0) {
            $rmaCollection = $this->rmaFactory->create()->getCollection()
                ->addFieldToFilter(self::ORDER_ID_COLUMN, $orderid);
            $orderIdCollection = $this->rmaFactory->create()->getCollection()
                ->addFieldToSelect(self::ORDER_ID_COLUMN)->getData();
            $orderIds = [];
            $i = 0;
            foreach ($orderIdCollection as $orderId) {
                $orderIds[$i] = $orderId[self::ORDER_ID_COLUMN];
                $i++;
            }

            $proids = [];
            if (in_array($orderid, $orderIds)) {
                $this->messageManager->addErrorMessage(__("You have already generated RMA for this $orderid order"));
                $result->setData([self::STATUS_FIELD => false]);
                return $result;
            } else {
                if (count($rmaCollection->getData()) > 0) {
                    $rmaproducts = [];
                    foreach ($rmaCollection as $key) {
                        $pro_ids = explode(",", $key[self::PRODUCT_ID_FIELD]);
                        array_push($rmaproducts, $pro_ids);
                        $proids[] = $key[self::PRODUCT_ID_FIELD];
                    }
                }
                $itemCollection = $_collection->getAllVisibleItems();
                $sku = [];
                $procount = 0;
                $rmacount = 0;
                foreach ($itemCollection as $item) {
                    if (in_array($item->getProductId(), $proids)) {
                        $procount = 1;
                        continue;
                    }
                    $rmacount = 1;
                    $product = $this->productFactory->create()->load($item->getProductId());
                    $imageUrl = $this->productImageHelper->create()
                        ->init($product, self::IMAGE_TYPE)
                        ->resize(self::PRODUCT_IMAGE_WIDTH, self::PRODUCT_IMAGE_HEIGHT)
                        ->getUrl();
                    
                    $html .= "
                        <div class='productdata_" . $item->getProductId() . "'>
                            <input type='hidden' name='productname[]' value='" . $product->getName() . "'>
                            <input type='hidden' name='productsku[]' value='" . $product->getSku() . "'>
                            <input type='hidden' name='productimage[]' value='" . $imageUrl . "'>
                            <div class='pro-images'>
                                <span class='checkbox'>
                                    <input type='checkbox' name='product_id[]' 
                                        value='" . $item->getProductId() . "'id='product_" . $item->getProductId() . "'>
                                    <span></span>
                                </span>
                                <span class='pro_img'><img src='" . $imageUrl . "'></span>
                            </div>
                            <div class='pro-name-sku'>
                                <span class='pro_name'>" . $product->getName() . "</span>
                                <span class='pro_sku'>" . $product->getSku() . "</span>
                            </div>
                            <select name='reason[]' id='reason_" .
                                $item->getProductId() . "' style='display:none;' required>
                                <option value=''>--- Select Option ---</option>" . $this->getReasons() . "
                            </select>
                        </div></div>
                    ";
                }
                if ($procount == 1 && $rmacount == 0) {
                    $html .= __("Your order number is not applicable.");
                }
            }
        } else {
            $html .= __("Your order number is not exist.");
        }
        $result->setData([self::STATUS_FIELD => true]);
        $result->setData([self::RESPONSE_TYPE => $html]);
        return $result;
    }

    /**
     * Initialize requested collection object
     *
     * @return Reason Collection
     */
    public function getReasons()
    {
        $reasonCollection = $this->rmareasonFactory->create()->getCollection();
        if ($reasonCollection->getSize()) {
            $result = '';
            foreach ($reasonCollection as $key) {
                $result .= "<option value=" . $key->getRmaReasonId() . ">" . $key->getName() . "</option>";
            }
            return $result;
        } else {
            return false;
        }
    }
}
