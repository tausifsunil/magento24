<?php

namespace Forever\Team\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Forever\Team\Model\TeamFactory;
use Magento\Framework\Controller\ResultFactory;
    
class Addnew extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Forever_Team::team';
    
    /**
     * @var \Forever\Team\Model\TeamFactory
     */
    private $entityFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Forever\Team\Model\EntityFactory $entityFactory
     */
    public function __construct(
        Context $context,
        TeamFactory $entityFactory
    ) {
        parent::__construct($context);
        $this->entityFactory = $entityFactory;
    }

    /**
     * Create new Entity
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $this->entityFactory->create();
        
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Forever_Team::first_level_menu');
        
        $title = "Team Information";
        
        $resultPage->getConfig()->getTitle()->prepend($title);
        
        return $resultPage;
    }
}
