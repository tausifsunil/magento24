<?php
/**
 * @author Ict Team
 * @copyright Copyright (c) 2017 Ict (http://icreativetechnologies.com/)
 * @package Ict_Shopbybrand
 */

namespace Ict\Shopbybrand\Controller\Adminhtml\Import;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action
{

    protected $_coreRegistry = null;

    protected $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry
    ) {
         $this->_coreRegistry = $registry;
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    
    /**
     * Index action
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Ict_Shopbybrand::import_shopbybrand');
        $resultPage->addBreadcrumb(__('ict'), __('Ict'));
        $resultPage->addBreadcrumb(__('Import Shopbybrand'), __('Manage Import Shopbybrand'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Import Shopbybrand'));
        return $resultPage;
    }

    /**
    * @return is allowed
    */

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Ict_Shopbybrand::makers');
    }
}
