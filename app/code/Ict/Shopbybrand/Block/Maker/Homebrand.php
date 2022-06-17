<?php
namespace Ict\Shopbybrand\Block\Maker;

use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\UrlFactory;
use Ict\Shopbybrand\Model\ResourceModel\Maker\CollectionFactory as MakerCollectionFactory;

class Homebrand extends Template
{

    protected $makerCollectionFactory;

    protected $_storeManager;

    protected $urlFactory;

    protected $FullActionname;

    public function __construct(
        MakerCollectionFactory $makerCollectionFactory,
        UrlFactory $urlFactory,
        \Magento\Framework\App\Action\Context $FullActionname,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Context $context,
        array $data = []
    )
    {
        $this->makerCollectionFactory = $makerCollectionFactory;
        $this->_storeManager = $storeManager;
        $this->urlFactory = $urlFactory;
        $this->_fullactionpath = $FullActionname;
        parent::__construct($context, $data);
    }

    /**
     * load the makers
     */
    protected  function _construct()
    {
        parent::_construct();
        /** @var \Ict\Shopbybrand\Model\ResourceModel\Maker\Collection $makers */
        $makers = $this->makerCollectionFactory->create()->addFieldToSelect('*')
            ->addFieldToFilter('is_active', 1)
            ->addFieldToFilter('is_featured', 1)
            ->addStoreFilter($this->_storeManager->getStore()->getId())
            ->setOrder('maker_id', 'DESC');
        $this->setMakers($makers);
    }

    /**
     * @return media URL for brands
     */
    public function getBaseUrl()
    { 
        return $this->_storeManager->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
            ).'ict/shopbybrand/maker/image' ;
    }

    /**
     * @return fullaction name
     */
    public function getFullActionName(){
        return $this->_fullactionpath->getRequest()->getFullActionName();
    }

    /**
     * @return Is_Featured from configuration
     */
    public function getIsFeatured(){
        return $this->_scopeConfig->getValue('ict_shopbybrand/maker/featured', ScopeInterface::SCOPE_STORE);
    }
}
