<?php
/**
 * A block that displays a list of questions
 *
 * @category   Forever
 * @package    Forever_Faq
 * @author     Andresa Martins <contact@andresa.dev>
 * @copyright  Copyright (c) 2019 Forever eCommerce Solutions (https://www.forever.com.br)
 * @license    http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Forever\Testimonials\Plugin\Block;

use Magento\Framework\Data\Tree\NodeFactory;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Topmenu
{
    const MODULE_ENABLE = 'testimonials/general/enable';
    /**
     * @var NodeFactory
     */
    protected $nodeFactory;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * Topmenu constructor.
     *
     * @param NodeFactory $nodeFactory
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        NodeFactory $nodeFactory,
        UrlInterface $urlBuilder,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->nodeFactory = $nodeFactory;
        $this->urlBuilder = $urlBuilder;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Add a new node to the end of the Top Menu
     *
     * @param \Magento\Theme\Block\Html\Topmenu $subject
     * @param string $outermostClass
     * @param string $childrenWrapClass
     * @param int $limit
     */
    public function beforeGetHtml(
        \Magento\Theme\Block\Html\Topmenu $subject,
        $outermostClass = '',
        $childrenWrapClass = '',
        $limit = 0
    ) {
        $node = $this->nodeFactory->create(
            [
                'data'    => $this->getNodeAsArray(),
                'idField' => 'id',
                'tree'    => $subject->getMenu()->getTree()
            ]
        );
        $subject->getMenu()->addChild($node);
    }

    /**
     * Create a new node to be added to the Top Menu
     *
     * @return array
     */
    protected function getNodeAsArray()
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $isEnableModule = $this->scopeConfig->getValue(self::MODULE_ENABLE, $storeScope);
        if ($isEnableModule == 1) {
            return [
                'name' => __('Testimonials'),
                'id'   => 'forever-testimonials',
                'url'  => $this->urlBuilder->getUrl('testimonials/'),
                'has_active' => false
            ];
        }
        
    }
}
