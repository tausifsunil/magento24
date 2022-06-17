<?php
namespace Product\Custom\ViewModel;

class Test implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    protected $helperData;

    public function __construct(
        \Magento\Catalog\Helper\Output $helperData
    ) {
        $this->helperData= $helperData;
    }
    public function getSettingValue()
    {
        return $this->helperData;
    }
}
