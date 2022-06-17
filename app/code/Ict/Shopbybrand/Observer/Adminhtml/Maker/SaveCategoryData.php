<?php
/**
 * @author Ict Team
 * @copyright Copyright (c) 2017 Ict (http://icreativetechnologies.com/)
 * @package Ict_Shopbybrand
 */
 
namespace Ict\Shopbybrand\Observer\Adminhtml\Maker;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Helper\Js as JsHelper;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Registry;
use Ict\Shopbybrand\Model\ResourceModel\Maker;

class SaveCategoryData extends Catalog implements ObserverInterface
{
    /**
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        $post = $this->context->getRequest()->getPostValue('category_ict_shopbybrand_makers', -1);
        if ($post != '-1') {
            $post = json_decode($post, true);
            $category = $this->coreRegistry->registry('category');
            $this->makerResource->saveMakerCategoryRelation($category, $post);
        }
        return $this;
    }
}
