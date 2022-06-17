<?php
/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_Quickorder
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */
 
namespace Ict\Quickorder\Controller\Index;

class Quickorder extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
    }
}
