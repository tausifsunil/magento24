<?php
/**
 * @author Ict Team
 * @copyright Copyright (c) 2017 Ict (http://icreativetechnologies.com/)
 * @package Ict_Shopbybrand
 */

namespace Ict\Shopbybrand\Controller\Adminhtml\Maker;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Ict\Shopbybrand\Model\ResourceModel\Maker\CollectionFactory as MakerCollectionFactory;

class MassExport extends Action
{

    protected $resultPageFactory;

    protected $resultPage;

    protected $makerCollectionFactory;

    protected $resultRedirectFactory;

    protected $successMessage = 'Mass Action successful on %1 records';

    protected $errorMessage = 'Mass Action failed';

    public function __construct(
        Context $context,
        MakerCollectionFactory $makerCollectionFactory,
        RedirectFactory $resultRedirectFactory,
        PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->makerCollectionFactory = $makerCollectionFactory;
        $this->resultRedirectFactory = $resultRedirectFactory;

    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {

        $ids = $this->getRequest()->getPost('selected');
        try {
            // $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $heading = array('name','url_key','banner_text','logo','banner','active','featured');    
            $outputFile = "shopbybrand_". date('Ymd_His').".csv";
            $handle = fopen($outputFile, 'w');
            fputcsv($handle, $heading);

            $makers = $this->makerCollectionFactory->create()->addFieldToFilter("maker_id", array("in"=>$ids));
            foreach ($makers as $maker) {
                $data = array($maker->getName(),$maker->getUrlKey(),$maker->getBannerText(),$maker->getAvatar(),$maker->getLogobanner(),$maker->getIsActive(),$maker->getIsFeatured());    
                fputcsv($handle, $data);
            }
            if (file_exists($outputFile)) {
                //set appropriate headers
                header('Content-Description: File Transfer');
                header('Content-Type: application/csv');
                header('Content-Disposition: attachment; filename='.basename($outputFile));
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($outputFile));
                ob_clean();flush();
                readfile($outputFile);
            } 
            $this->messageManager->addSuccess(__($this->successMessage));
        } catch (LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __($this->errorMessage));
        }
        $redirectResult = $this->resultRedirectFactory->create();
        $redirectResult->setPath('ict_shopbybrand/*/index');
        return $redirectResult;
    }
}
