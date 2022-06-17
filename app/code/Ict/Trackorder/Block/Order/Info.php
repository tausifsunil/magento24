<?php

/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_Trackorder
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */

namespace Ict\Trackorder\Block\Order;

class Info extends \Magento\Sales\Block\Order\Info
{
    public function getConfigValue($path)
    {
        return $this->_scopeConfig->getValue($path);
    }
}
