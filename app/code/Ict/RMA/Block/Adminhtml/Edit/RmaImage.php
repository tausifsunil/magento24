<?php

/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_RMA
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */

namespace Ict\RMA\Block\Adminhtml\Edit;

class RmaImage extends \Magento\Backend\Block\Template
{
    public const REQUEST_PARAM = 'id';

    /**
     * @var \Ict\RMA\Model\Rma
     */
    protected $rma;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var string
     */
    protected $_template = 'rmaImage.phtml';

    /**
     * @param Ict\RMA\Model\Rma $rma
     * @param Magento\Framework\App\Request\Http $request
     * @param Magento\Store\Model\StoreManagerInterface $storeManager
     * @param Magento\Backend\Block\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Ict\RMA\Model\Rma $rma,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        $this->rma = $rma;
        $this->request = $request;
        $this->storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    /**
     * Get Rma Collection
     *
     * @return RMA Collection
     */
    public function getRmaCollection()
    {
        $id = $this->request->getParam(self::REQUEST_PARAM);
        $rmaCollection = $this->rma->load($id);
        return $rmaCollection;
    }

    /**
     * Get Rma Image
     *
     * @return Image
     */
    public function getRmaImage()
    {
        $rmaImage = $this->getRmaCollection()->getFileUploads();
        if ($rmaImage != 'null') {
            $mediaPath = $this->storeManager->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
            ) . 'RMA';
            return $mediaPath . $rmaImage;
        } else {
            return 0;
        }
    }
}
