<?php

/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_RMA
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */

namespace Ict\RMA\Block\Adminhtml\Edit;

class Message extends \Magento\Backend\Block\Template
{
    /**
     * Request Params
     */
    public const REQUEST_PARAMS = 'id';
    
    /**
     * RMA Id
     */
    public const RMA_ID = 'rma_id';

    /**
     * @var \Ict\RMA\Model\Rma
     */
    protected $rma;

    /**
     * @var \Ict\RMA\Model\RmaMessageFactory
     */
    protected $rmaMessageFactory;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var string
     */
    protected $_template = 'message.phtml';

    /**
     * @param Ict\RMA\Model\Rma $rma
     * @param Ict\RMA\Model\RmaMessageFactory $rmamessageFactory
     * @param Magento\Framework\App\Request\Http $request
     * @param Magento\Backend\Block\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Ict\RMA\Model\Rma $rma,
        \Ict\RMA\Model\RmaMessageFactory $rmamessageFactory,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        $this->rma = $rma;
        $this->rmamessageFactory = $rmamessageFactory;
        $this->request = $request;
        parent::__construct($context, $data);
    }

    /**
     * Get Rma Collection
     *
     * @return RMA Collection
     */
    public function getRmaCollection()
    {
        $id = $this->request->getParam(self::REQUEST_PARAMS);
        $rmaCollection = $this->rma->load($id);
        return $rmaCollection;
    }

    /**
     * Get Message Collection
     *
     * @return Message Collection
     */
    public function getMessageCollection()
    {
        $id = $this->request->getParam(self::REQUEST_PARAMS);
        $rmaMessageCollection =  $this->rmamessageFactory->create()->getCollection()
            ->addFieldToFilter(self::RMA_ID, ['eq' => $id]);
        return $rmaMessageCollection;
    }
}
