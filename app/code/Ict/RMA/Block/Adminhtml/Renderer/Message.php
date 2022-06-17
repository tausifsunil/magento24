<?php

/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_RMA
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */

namespace Ict\RMA\Block\Adminhtml\Renderer;

class Message extends \Magento\Framework\Data\Form\Element\AbstractElement
{
    /**
     * @var Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var Ict\RMA\Model\Rma
     */
    protected $rma;

    /**
     * @var Ict\RMA\Model\RmaMessage
     */
    protected $rmaMessageFactory;

    /**
     * @param Magento\Framework\App\Request\Http $request
     * @param Ict\RMA\Model\Rma $rma
     * @param Ict\RMA\Model\RmaMessage $rmamessageFactory
     */
    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Ict\RMA\Model\Rma $rma,
        \Ict\RMA\Model\RmaMessage $rmamessageFactory
    ) {
        $this->request = $request;
        $this->rma = $rma;
        $this->rmamessageFactory = $rmamessageFactory;
    }

    /**
     * Get the after element html.
     *
     * @return mixed | Html
     */
    public function getAfterElementHtml()
    {
        $id = $this->request->getParam('rma_id');
        $rma = $this->rma->load($id);
        $rmaMessageCollection =  $this->rmamessageFactory->create()->getCollection()->addFieldToFilter('rma_id', $id);
        $customDiv = '<div style="width:600px;height:200px;margin:10px 0;" id="messagediv"><h3>Additional Info</h3>';
        
        foreach ($rmaMessageCollection as $message) {
            if ($message->getType() == 'customer') {
                $customDiv .= "<div class='message-lefts'>
                    <div class='customer_names'>
                        <span class='customer_name'>" . $rma->getCustomerEmail() . "</span>
                    </div>
                    <div class='messages'>
                        <span class='msg'>" . $message->getMessage() . "</span>
                        <span class='created_at'>" . $message->getCreatedAt() . "</span>
                    </div>
                    </div>";
            } elseif ($message->getType() == 'admin') {
                $customDiv .= "<div class='message-rights'>
                    <div class='customer_names'>
                        <span class='customer_name'>ADMIN</span>
                    </div>
                    <div class='messages'>
                        <span class='msg'>" . $message->getMessage() . "</span>
                        <span class='created_at'>" . $message->getCreatedAt() . "</span>
                    </div>
                    </div>";
            }
        }
        $customDiv .= '</div>';
        return $customDiv;
    }
}
