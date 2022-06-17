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

class SaveProductData extends Catalog implements ObserverInterface
{
    /**
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        $post = $this->context->getRequest()->getPostValue('makers', -1);
        if ($post != '-1') {
            $post = $this->jsHelper->decodeGridSerializedInput($post);
            $product = $this->coreRegistry->registry('product');
            $this->makerResource->saveMakerProductRelation($product, $post);
        }
        return $this;
    }
}
