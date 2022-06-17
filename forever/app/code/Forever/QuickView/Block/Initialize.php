<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Forever\QuickView\Block;

use Forever\QuickView\Model\Config as QuickViewConfig;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class Initialize block init the js
 */
class Initialize extends Template
{
    /** @var QuickViewConfig */
    protected $config;

    /**
     * @param QuickViewConfig $config
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        QuickViewConfig $config,
        Context $context,
        array $data = []
    ) {
        $this->config = $config;
        parent::__construct($context, $data);
    }

    /**
     * @return array
     * @throws NoSuchEntityException
     */
    public function getConfig()
    {
        return [
            'baseUrl' => $this->_storeManager->getStore()->getBaseUrl()
        ];
    }
}
