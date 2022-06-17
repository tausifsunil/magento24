<?php

/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_Trackorder
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */

namespace Ict\Trackorder\ViewModel;

class TrackorderViewModel implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var \Magento\Tax\Helper\Data
     */
    protected $data;

    /**
     * @var \Magento\GiftMessage\Helper\Message
     */
    protected $message;

    /**
     * @param \Magento\Tax\Helper\Data $data
     * @param \Magento\GiftMessage\Helper\Message $message
     */
    public function __construct(
        \Magento\Tax\Helper\Data $data,
        \Magento\GiftMessage\Helper\Message $message
    ) {
        $this->data = $data;
        $this->message = $message;
    }

    /**
     * Get calculated taxes for each tax class
     *
     * @return array
     */
    public function getCalculatedTaxes($source)
    {
        return $this->data->getCalculatedTaxes($source);
    }

    /**
     * Check if giftmessages is allowed for specified entity.
     *
     * @param string $type
     * @param \Magento\Framework\DataObject $entity
     * @param \Magento\Store\Model\Store|int|null $store
     * @return bool|string|null
     */
    public function getIsMessagesAllowed($type, \Magento\Framework\DataObject $entity, $store = null)
    {
        return $this->message->isMessagesAllowed($type, $entity);
    }

    /**
     * Retrieve gift message for entity. If message not exists return null
     *
     * @param \Magento\Framework\DataObject $entity
     * @return \Magento\GiftMessage\Model\Message
     */
    public function getGiftMessageForEntity(\Magento\Framework\DataObject $entity)
    {
        return $this->message->getGiftMessageForEntity($item);
    }

    /**
     * Retrieve escaped and preformated gift message text for specified entity
     *
     * @param \Magento\Framework\DataObject $entity
     * @return string|null
     */
    public function getEscapedGiftMessage(\Magento\Framework\DataObject $entity)
    {
        return $this->message->getEscapedGiftMessage($entity);
    }
}
