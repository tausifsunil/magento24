<?php

namespace Forever\AuthenticationPopUp\Plugin;

use Magento\Customer\Model\Context;
use Forever\AuthenticationPopUp\Helper\Data as AjaxLoginHelper;

class SigninLinkPlugin
{
    /**
     * Customer session
     *
     * @var \Magento\Framework\App\Http\Context
     */
    protected $httpContext;

    /**
     * @var AjaxLoginHelper
     */
    protected $helper;

    /**
     * SigninLinkPlugin constructor.
     *
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param AjaxLoginHelper $helper
     */
    public function __construct(
        \Magento\Framework\App\Http\Context $httpContext,
        AjaxLoginHelper $helper
    ) {
        $this->httpContext = $httpContext;
        $this->helper = $helper;
    }

    /**
     * @param \Magento\Customer\Block\Account\AuthorizationLink $subject
     * @param $result
     * @return string
     */
    public function afterGetHref(\Magento\Customer\Block\Account\AuthorizationLink $subject, $result)
    {
        if ($this->helper->isModuleEnabled()) {
            if (!$this->isLoggedIn()) {
                $result = '#';
            }
        }
        return $result;
    }

    /**
     * Check if customer is logged in
     *
     * @return bool
     */
    public function isLoggedIn()
    {
        return $this->httpContext->getValue(Context::CONTEXT_AUTH);
    }
}
