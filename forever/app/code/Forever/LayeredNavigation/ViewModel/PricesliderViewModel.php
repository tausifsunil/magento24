<?php

namespace Forever\LayeredNavigation\ViewModel;

class PricesliderViewModel implements \Magento\Framework\View\Element\Block\ArgumentInterface
{

    /**
     * @var array
     */
    protected $configModule;

    /**
     * @var \Forever\LayeredNavigation\Helper\Configdata
     * */
    protected $_moduleHelper;

    /**
     * @param Magento\Catalog\Helper\Data $data
     * @param Magento\Tax\Helper\Data $texdata
     * @param Magento\Framework\Json\Helper\Data $jsondata
     * @param Forever\LayeredNavigation\Helper\Configdata $moduleHelper
     * */
    public function __construct(
        \Magento\Catalog\Helper\Data $data,
        \Magento\Tax\Helper\Data $texdata,
        \Magento\Framework\Json\Helper\Data $jsondata,
        \Forever\LayeredNavigation\Helper\Configdata $moduleHelper
    ) {
        $this->_data = $data;
        $this->texdata = $texdata;
        $this->jsondata = $jsondata;
        $this->_moduleHelper = $moduleHelper;
    }

    /**
     * this funcation return count
     * @return int
     * */
    public function shouldDisplayProductCountOnLayer()
    {
        return $this->_data->shouldDisplayProductCountOnLayer();
    }

    /**
     * @param $value
     *
     * @return Priceformat
     * */
    public function getPriceFormat($value)
    {
        return $this->texdata->getPriceFormat($value);
    }

    /**
     * @return Jsondata | Magento\Framework\Json\Helper\Data
     * */
    public function getjsondata()
    {
        return $this->jsondata;
    }

    /**
     * Check Configuration value
     *
     * @return bool
     * */
    public function getSlider()
    {
        return $this->_moduleHelper->isEnabled();
    }
}
