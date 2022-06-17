<?php
/**
 * @author Ict Team
 * @copyright Copyright (c) 2017 Ict (http://icreativetechnologies.com/)
 * @package Ict_Shopbybrand
 */

namespace Ict\Shopbybrand\Block\Maker;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;

class ViewMaker extends Template
{

    protected $coreRegistry;

    protected $_storeManager;

    public function __construct(
        Context $context,
        Registry $registry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = []
    )
    {
        $this->coreRegistry = $registry;
        $this->_storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    /**
     * get current maker
     * @return \Ict\Shopbybrand\Model\Maker
     */
    public function getCurrentMaker()
    {
        return $this->coreRegistry->registry('current_maker');
    }

    /**
     * @return Media URL for brands
     */
    public function getBaseUrl()
    { 
        return $this->_storeManager->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
            ).'ict/shopbybrand/maker/image' ;
    }
}
