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
class ListProductCrosssellPlugin
{
    /** @var Registry */
    private $registry;

    /** @var Renderer */
    private $renderer;

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
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/custom.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);
        $logger->info("cron runs");
        if (!$this->registry->registry('forever_category_observer')
            && !$subject->getIsForeverQuickViewObserved()
        ) {
            $crossProducts = $subject->getItems();
            if ($crossProducts) {
                foreach ($crossProducts as $product) {
                    $result .= $this->renderer->renderProductQuickViewButton($product, 'category');
                }
                $subject->setIsForeverQuickViewObserved(true);
            }
        }
        return $result;
    }
}
