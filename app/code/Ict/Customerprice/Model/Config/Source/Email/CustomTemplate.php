<?php

/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_Customerprice
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */

namespace Ict\Customerprice\Model\Config\Source\Email;

class CustomTemplate
{
    /**
     * @var \Magento\Email\Model\ResourceModel\Template\CollectionFactory
     */
    private $templatesFactory;

    /**
     * @param \Magento\Email\Model\ResourceModel\Template\CollectionFactory $templatesFactory
     */
    public function __construct(
        \Magento\Email\Model\ResourceModel\Template\CollectionFactory $templatesFactory
    ) {
        $this->templatesFactory = $templatesFactory;
    }

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        $collection = $this->templatesFactory->create();
        $collection->load();
        $options = $collection->toOptionArray();
        array_unshift($options, ['value' => 0, 'label' => 'Default Template (Request for Quote)']);
        return $options;
    }
}
