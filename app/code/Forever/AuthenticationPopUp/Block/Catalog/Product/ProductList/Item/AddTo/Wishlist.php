<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Forever\AuthenticationPopUp\Block\Catalog\Product\ProductList\Item\AddTo;

/**
 * Add product to wishlist
 *
 * @api
 * @since 100.1.1
 */
class Wishlist extends \Magento\Catalog\Block\Product\ProductList\Item\Block
{
    /**
     * @return \Magento\Wishlist\Helper\Data
     * @since 100.1.1
     */
    public function getWishlistHelper()
    {
        // echo 123;die;
        return $this->_wishlistHelper;
    }
}
