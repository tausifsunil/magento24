<?php

/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_RMA
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */

namespace Ict\RMA\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Resolution implements ArrayInterface
{
    /**
     * value of array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
                ['value' => '0', 'label' => __('-- Select Resolution--')],
                ['value' => '1', 'label' => __('Refund')],
                ['value' => '2', 'label' => __('Replacement')],
            ];
    }
}
