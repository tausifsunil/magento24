<?php

/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_Cancelorder
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */

namespace Ict\Cancelorder\Block;

class Cancel extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\App\Response\RedirectInterface $referer
     */
    private $referer;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\App\Response\RedirectInterface $referer
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Response\RedirectInterface $referer
    ) {
        $this->referer = $referer;
        parent::__construct($context);
    }

    /**
     * Get order id
     *
     * @return string
     */
    public function getOrderId()
    {
        return $this->getOrderdata();
    }

    /**
     * Get base url
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }

    /**
     * Get referer url
     *
     * @return string
     */
    public function getRefererUrl()
    {
        return $this->referer->getRefererUrl();
    }

    /**
     * Get config value
     *
     * @param string $value
     * @return string
     */
    public function getConfig($value)
    {
        return $this->_scopeConfig->getValue(
            $value,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
