<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Forever\QuickView\Plugin\Catalog\Product;

use Magento\Framework\Registry;
use Forever\QuickView\Model\Renderer;

/**
 * Class ListProductPlugin append html to list
 */
class ListProductNewArrivalPlugin
{
    /** @var Registry */
    private $registry;

    /** @var Renderer */
    private $renderer;

    // private $productFactory;

    /**
     * ListProduct constructor.
     * @param Registry $registry
     * @param Renderer $renderer
     */
    public function __construct(
        Registry $registry,
        Renderer $renderer
    ) {
        $this->registry = $registry;
        $this->renderer = $renderer;
    }

    /**
     * @param  $subject
     * @param $result
     * @return string
     * @noinspection PhpUndefinedMethodInspection
     */
    public function afterToHtml(
        $subject,
        $result
    ) {
        if (!$this->registry->registry('forever_category_observer')
            && !$subject->getIsForeverQuickViewObserved()
        ) {
            $products = $subject->getProductCollection();
            if ($products) {
                // $relatedproducts = $products->getCategoryProducts();
                foreach ($products as $product) {
                    $result .= $this->renderer->renderProductQuickViewButton($product, 'category');
                }
                $subject->setIsForeverQuickViewObserved(true);
            }
        }
        return $result;
    }   
}
