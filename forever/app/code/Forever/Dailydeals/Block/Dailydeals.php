<?php

namespace Forever\Dailydeals\Block;

use Magento\Store\Model\ScopeInterface;

class Dailydeals extends \Magento\Framework\View\Element\Template
{
    const ENABLE = 'dailydeals/general/enabled';
    const PRODUCTSKU = 'dailydeals/general/dailydeals_productsku';
    const EXPDATETIME = 'dailydeals/general/dailydeals_exptime';
    const SALETEXT = 'dailydeals/general/dailydeals_saletext';
    const BUTTONTEXT = 'dailydeals/general/dailydeals_buttontext';
    const THUMBNAIL_IMAGE = 'product_thumbnail_image';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $config;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;

    /**
     * @var \Magento\Catalog\Block\Product\ListProduct
     */
    protected $listProduct;

    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    protected $currencySymbol;

    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $productImage;

    /**
     * @param Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param Magento\Store\Model\StoreManagerInterface $storeManager
     * @param Magento\Catalog\Model\ProductRepository $productRepository
     * @param Magento\Catalog\Block\Product\ListProduct $listProduct
     * @param Magento\Framework\Pricing\PriceCurrencyInterface $currencySymbol
     * @param Magento\Catalog\Helper\Image $productImage
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Catalog\Block\Product\ListProduct $listProduct,
        \Magento\Framework\Pricing\PriceCurrencyInterface $currencySymbol,
        \Magento\Catalog\Helper\Image $productImage
    ) {
        parent::__construct($context);
        $this->config = $config;
        $this->storeManager = $storeManager;
        $this->productRepository = $productRepository;
        $this->listProduct = $listProduct;
        $this->currencySymbol = $currencySymbol;
        $this->productImage = $productImage;
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

    /**
     * @return Scope Config Value | string
     */
    public function getConfigSKU()
    {
        return $this->getConfigData(self::PRODUCTSKU);
    }

    /**
     * @return Scope Config Value | bool
     */
    public function isEnable()
    {
        return $this->getConfigData(self::ENABLE);
    }

    /**
     * @return Scope Config Value | string
     */
    public function getConfigExpDateTime()
    {
        return $this->getConfigData(self::EXPDATETIME);
    }

    /**
     * @return Scope Config Value | string
     */
    public function getConfigSaleText()
    {
        return $this->getConfigData(self::SALETEXT);
    }

    /**
     * @return Scope Config Value | string
     */
    public function getConfigButtonText()
    {
        return $this->getConfigData(self::BUTTONTEXT);
    }

    /**
     * @return Product | object
     */
    public function getSpecificProduct()
    {
        $sku = $this->getConfigSKU();
        $product = $this->productRepository->get($sku);
        return $product;
    }

    /**
     * @return Product Image URL | string
     */
    public function getProductImage($product)
    {
        $imageUrl = $this->productImage->init(
            $product,
            self::THUMBNAIL_IMAGE
        )->setImageFile(
            $product->getFile()
        )->resize(
            200,
            200
        )->getUrl();
        return $imageUrl;
    }

    /**
     * @return Cart URL | string
     */
    public function getAddCartUrl($product)
    {
        return $this->listProduct->getAddToCartUrl($product);
    }

    /**
     * @return Currency Symbol
     */
    public function getPriceSymbol()
    {
        return $this->currencySymbol->getCurrencySymbol(
            $this->storeManager->getStore()->getStoreId()
        );
    }
}
