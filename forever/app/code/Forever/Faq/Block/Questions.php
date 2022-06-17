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

namespace Forever\Faq\Block;

use Magento\Framework\View\Element\Template;
use Forever\Faq\Model\ResourceModel\Question\CollectionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Questions extends Template
{
    /*
    * This label won't be displayed in the frontend block
    */
    const MAIN_LABEL = 'Default';

    const MODULE_ENABLE = 'faq/general/enable';

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    public function __construct(
        Template\Context $context,
        CollectionFactory $collectionFactory,
        ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->collectionFactory = $collectionFactory;
        $this->scopeConfig = $scopeConfig;
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
    public function getConfigValue()
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue(self::MODULE_ENABLE, $storeScope);
    }
}
