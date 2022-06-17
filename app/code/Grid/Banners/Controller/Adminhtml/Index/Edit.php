<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Created By: MageDelight Pvt. Ltd.
 */
namespace Grid\Banners\Controller\Adminhtml\Index;
use Magento\Framework\Controller\ResultFactory;
class Edit extends \Magento\Backend\App\Action {
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute() {
       $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
       $resultPage->getConfig()->getTitle()->prepend(__('Edit Record'));
       // print_r(json_decode(json_encode($resultPage),1));
       // die;
       return $resultPage;
    }
}