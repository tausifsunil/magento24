<?php

/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_RMA
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */

namespace Ict\RMA\Block\Adminhtml\Edit;

class Products extends \Magento\Backend\Block\Template
{
    /**
     * Request Param
     */
    public const REQUEST_PARAM = 'id';

    /**
     * Product Image Type
     */
    public const PRODUCT_IMAGE_TYPE = 'product_thumbnail_image';

    /**
     * @var \Ict\RMA\Model\Rma
     */
    protected $rma;

    /**
     * @var \Ict\RMA\Model\RmaReason
     */
    protected $rmaReason;

    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $imageHelper;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var string
     */
    protected $_template = 'products.phtml';

    /**
     * @param Ict\RMA\Model\Rma $rma
     * @param Ict\RMA\Model\RmaReason $rmaReason
     * @param Magento\Catalog\Helper\Image $imageHelper
     * @param Magento\Catalog\Model\ProductFactory $productFactory
     * @param Magento\Framework\App\Request\Http $request
     * @param Magento\Backend\Block\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Ict\RMA\Model\Rma $rma,
        \Ict\RMA\Model\RmaReason $rmaReason,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        $this->rma = $rma;
        $this->rmaReason = $rmaReason;
        $this->imageHelper = $imageHelper;
        $this->productFactory = $productFactory;
        $this->request = $request;
        parent::__construct($context, $data);
    }

    /**
     * Get Rma Collection
     *
     * @param Numeric $id
     * @return RMA Collection
     */
    public function getRmaCollection()
    {
        $id = $this->request->getParam(self::REQUEST_PARAM);
        $rmaCollection = $this->rma->load($id);
        return $rmaCollection;
    }

    /**
     * Get Rma Reason
     *
     * @param Numeric $id
     * @return RMA Reason
     */
    public function getRmaReason($id)
    {
        $rmaReasonCollection = $this->rmaReason->load($id);
        return $rmaReasonCollection->getName();
    }

    /**
     * Get Rma Product Ids
     *
     * @param Numeric $id
     * @return Product Ids
     */
    public function getProductIds()
    {
        $productIds = [];
        $rmaProductIds = $this->getRmaCollection()->getProductId();
        $productIds = explode(",", $rmaProductIds);
        return $productIds;
    }

    /**
     * Get Rma Product Image Url
     *
     * @param Numeric $id
     * @return Product Image
     */
    public function getProductImageUrl($id)
    {
        $product = $this->productFactory->create()->load($id);
        $url = $this->imageHelper->init($product, self::PRODUCT_IMAGE_TYPE)->getUrl();
        return $url;
    }

    /**
     * Get Rma Product Name
     *
     * @param Numeric $id
     * @return Product Name
     */
    public function getRmaProductName($id)
    {
        $product = $this->productFactory->create()->load($id);
        $name = $product->getName();
        return $name;
    }

    /**
     * Get Rma Product Sku
     *
     * @param Numeric $id
     * @return Product SKU
     */
    public function getRmaProductSku($id)
    {
        $product = $this->productFactory->create()->load($id);
        $sku = $product->getSku();
        return $sku;
    }
}
