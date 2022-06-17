<?php

/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_Cancelorder
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */

namespace Ict\Cancelorder\Model\Config\Source\Email;

class CustomTemplate
{
    /**
     * @var \Magento\Email\Model\ResourceModel\Template\CollectionFactory $templatesFactory
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
     * Get the list of custom email templates
     *
     * @return array
     */
    public function toOptionArray()
    {
        $collection = $this->templatesFactory->create();
        $collection->load();
        $options = $collection->toOptionArray();
        array_unshift(
            $options,
            [
                'value' => 0,
                'label' => 'Default Template (Cancel Order)'
            ]
        );
        return $options;
    }
}
