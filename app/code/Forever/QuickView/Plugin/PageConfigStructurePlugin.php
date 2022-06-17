<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Forever\QuickView\Plugin;

use Magento\Framework\View\Page\Config\Structure;
use Forever\QuickView\Model\Config as QuickViewConfig;

/**
 * Class PageConfigStructurePlugin remove asset depends configuration
 */
class PageConfigStructurePlugin
{

    /** @var QuickViewConfig */
    // protected $config;

    /**
     * PageConfigStructurePlugin constructor.
     * @param QuickViewConfig $config
     */
    public function __construct(
        QuickViewConfig $config
    ) {
        $this->config = $config;
    }

    /**
     * @param Structure $subject
     * @param string $name
     * @param array $attributes
     * @return array
     */
    public function beforeAddAssets(
        Structure $subject,
        $name,
        $attributes
    ) {
        if (!$this->config->isEnabled()) {
            switch ($name) {
                case 'Forever_QuickView::css/magnific-popup.css':
                    $subject->removeAssets($name);
                    break;
            }
        }

        return [$name, $attributes];
    }
}
