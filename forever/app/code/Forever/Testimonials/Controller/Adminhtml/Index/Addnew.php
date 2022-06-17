<?php
/** 
 * @package   Forever_Testimonials
 * @author    Ricky Thakur (Kapil Dev Singh)
 * @copyright Copyright (c) 2018 Ricky Thakur
 * @license   MIT license (see LICENSE.txt for details)
 */
namespace Forever\Testimonials\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Forever\Testimonials\Model\TestimonialsFactory;
use Magento\Framework\Controller\ResultFactory;
    
class Addnew extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Forever_Testimonials::testimonials';

    
    /**
     * @var \Forever\Testimonials\Model\TestimonialsFactory
     */
    private $entityFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Forever\Testimonials\Model\EntityFactory $entityFactory
     */
    public function __construct(
        Context $context,
        TestimonialsFactory $entityFactory
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
        $resultPage->setActiveMenu('Forever_Testimonials::first_level_menu');
        
        $title = "Testimonials Information";
        
        $resultPage->getConfig()->getTitle()->prepend($title);
        
        return $resultPage;
    }
}  