<?php
/**
 * A block that displays a list of questions
 *
 * @category   Forever
 * @package    Forever_Faq
 * @author     Andresa Martins <contact@andresa.dev>
 * @copyright  Copyright (c) 2019 Forever eCommerce Solutions (https://www.forever.com.br)
 * @license    http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Forever\Testimonials\Block;

use Magento\Framework\View\Element\Template;
use Forever\Testimonials\Model\ResourceModel\Testimonials\CollectionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;

class Testimonials extends Template
{
    /*
    * This label won't be displayed in the frontend block
    */
    const MAIN_LABEL = 'Default';

    const MODULE_ENABLE = 'testimonials/general/enable';

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    protected $storeManager;

    public function __construct(
        Template\Context $context,
        CollectionFactory $collectionFactory,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->collectionFactory = $collectionFactory;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * Get All Questions
     *
     * @return \Magento\Framework\DataObject[]
     */
    public function getItems()
    {
        $questionCollection = $this->collectionFactory->create();
        $questionCollection->addFieldToFilter('main_table.status', 1);

        return $questionCollection->getItems();
    }
    public function getMediaUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }
    public function getConfigValue()
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue(self::MODULE_ENABLE, $storeScope);
    }
}
