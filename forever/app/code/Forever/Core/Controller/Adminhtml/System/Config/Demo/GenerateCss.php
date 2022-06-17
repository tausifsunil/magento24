<?php
namespace Forever\Core\Controller\Adminhtml\System\Config\Demo;
use Magento\Framework\Controller\ResultFactory;

class GenerateCss extends \Magento\Backend\App\Action {
    protected $_objectManager;
    protected $_publicActions = ['generatecss'];

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        parent::__construct($context);

        $this->_objectManager= $objectManager;
    }
    public function execute()
    {
        $this->_objectManager->get('Forever\Core\Model\Cssconfig\Generator')->generateCss('css','','');

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }
}