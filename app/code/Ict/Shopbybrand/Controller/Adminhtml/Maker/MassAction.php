<?php
/**
 * @author Ict Team
 * @copyright Copyright (c) 2017 Ict (http://icreativetechnologies.com/)
 * @package Ict_Shopbybrand
 */

namespace Ict\Shopbybrand\Controller\Adminhtml\Maker;

use Magento\Framework\Exception\LocalizedException;
use Ict\Shopbybrand\Controller\Adminhtml\Maker;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Ict\Shopbybrand\Model\MakerFactory;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\Filter\Date;
use Magento\Ui\Component\MassAction\Filter;
use Ict\Shopbybrand\Model\ResourceModel\Maker\CollectionFactory;
use Ict\Shopbybrand\Model\Maker as MakerModel;

abstract class MassAction extends Maker
{
    protected $filter;

    protected $collectionFactory;
    
    protected $successMessage = 'Mass Action successful on %1 records';
    
    protected $errorMessage = 'Mass Action failed';

    public function __construct(
        Filter $filter,
        CollectionFactory $collectionFactory,
        Registry $registry,
        MakerFactory $makerFactory,
        RedirectFactory $resultRedirectFactory,
        Date $dateFilter,
        Context $context
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($registry, $makerFactory, $resultRedirectFactory, $dateFilter, $context);
    }

    /**
     * @param MakerModel $maker
     * @return mixed
     */
    protected abstract function doTheAction(MakerModel $maker);

    /**
     * execute action
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $collectionSize = $collection->getSize();
            foreach ($collection as $maker) {
                $this->doTheAction($maker);
            }
            $this->messageManager->addSuccess(__($this->successMessage, $collectionSize));
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
