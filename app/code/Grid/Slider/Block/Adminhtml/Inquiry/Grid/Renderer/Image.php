<?php

namespace Grid\Slider\Block\Adminhtml\Inquiry\Grid\Renderer;
use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\DataObject;
use Magento\Store\Model\StoreManagerInterface;

class Image extends AbstractRenderer
{
    private $_storeManager;
    /**
     * @param \Magento\Backend\Block\Context $context
     * @param array $data
     */
    public function __construct(\Magento\Backend\Block\Context $context, StoreManagerInterface $storemanager, array $data = [])
    {
        $this->_storeManager = $storemanager;
        parent::__construct($context, $data);
        $this->_authorization = $context->getAuthorization();
    }
    /**
     * Renders grid column
     *
     * @param Object $row
     * @return  string
     */
    public function render(DataObject $row)
    {
        $mediaDirectory = $this->_storeManager->getStore()->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        );
        $imageUrl = $mediaDirectory.'banners/image/'.$this->_getValue($row);
        return '<img src="'.$imageUrl.'" width="50" height="50"/>';
    }
}

// http://127.0.0.1/m235/pub/media/banners/image/greps3_3.jpg

// http://127.0.0.1/m235/pub/media//inquiry/imagesgreps3_3.jpg
// 
// http://127.0.0.1/m235/pub/media/banners/images/mango2_8.jpg