<?php
/**
 * @author Ict Team
 * @copyright Copyright (c) 2017 Ict (http://icreativetechnologies.com/)
 * @package Ict_Shopbybrand
 */

namespace Ict\Shopbybrand\Observer\Adminhtml\Maker;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class AddCategoryTab implements ObserverInterface
{
 
    public function execute(Observer $observer)
    {
        /** @var \Magento\Catalog\Block\Adminhtml\Category\Tabs $tabs */
        $tabs = $observer->getEvent()->getTabs();
        $container = $tabs->getLayout()->createBlock(
            'Magento\Backend\Block\Template',
            'category.maker.grid.wrapper'
        );
        /** @var \Magento\Backend\Block\Template  $container */
        $container->setTemplate('Ict_Shopbybrand::catalog/category/maker.phtml');
        $tab = $tabs->getLayout()->createBlock(
            'Ict\Shopbybrand\Block\Adminhtml\Catalog\Category\Tab\Maker',
            'category.ict_shopbybrand.maker.grid'
        );

        $container->setChild('grid', $tab);
        $content = $container->toHtml();
        $tabs->addTab('ict_shopbybrand_makers', array(
            'label'     => __('Categories'),
            'content'   => $content,
        ));
        return $this;
    }
}
