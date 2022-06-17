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

abstract class Catalog implements ObserverInterface
{

    protected $context;

    protected $makerResource;

    protected $coreRegistry;

    protected $jsHelper;

    public function __construct(
        Context $context,
        Maker $makerResource,
        JsHelper $jsHelper,
        Registry $coreRegistry
    )
    {
        $this->context        = $context;
        $this->makerResource = $makerResource;
        $this->jsHelper       = $jsHelper;
        $this->coreRegistry   = $coreRegistry;
    }
}
