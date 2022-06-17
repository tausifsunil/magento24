<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Forever\QuickView\Plugin;

use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Catalog\Block\Product\AbstractProduct;

/**
 * Class AbstractProductPlugin determine the handle
 */
class AbstractProductPlugin
{
    /** @var HttpRequest */
    protected $request;

    /**
     * AbstractProduct constructor.
     * @param HttpRequest $request
     */
    public function __construct(HttpRequest $request)
    {
        $this->request = $request;
    }

    /**
     * @param AbstractProduct $subject
     * @param $result
     * @return bool
     */
    public function afterIsRedirectToCartEnabled(
        AbstractProduct $subject,
        $result
    ) {
        $requestUri = $this->request->getRequestUri();

        if (strpos($requestUri, 'quickview/catalog_product/view') !== false) {
            return false;
        }

        return $result;
    }
}
