<?php
/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_Quickorder
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */

namespace Ict\Quickorder\Block\Index;

class Index extends \Magento\Framework\View\Element\Template
{
    /**
     * Get configurable product options
     */
    public function getProductOptions()
    {
        return $this->getProducts();
    }
}
