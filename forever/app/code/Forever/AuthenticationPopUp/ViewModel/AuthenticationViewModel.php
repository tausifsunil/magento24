<?php

namespace Forever\AuthenticationPopUp\ViewModel;
use Forever\AuthenticationPopUp\Helper\Data as AjaxLoginHelper;

class AuthenticationViewModel implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    protected $helper;
  
    /**
     * @param \Magento\Framework\App\Request\Http $request
     */
    public function __construct(
        AjaxLoginHelper $helper
    ) {

        $this->helper = $helper;
    }

    /**
     * @param $value
     *
     * @return bool
     */
    public function getScopeconfig()
    {   
       return $this->helper->isModuleEnabled();
    }
}
