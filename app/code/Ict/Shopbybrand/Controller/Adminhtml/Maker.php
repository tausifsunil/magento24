<?php
/**
 * @author Ict Team
 * @copyright Copyright (c) 2017 Ict (http://icreativetechnologies.com/)
 * @package Ict_Shopbybrand
 */

namespace Ict\Shopbybrand\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Ict\Shopbybrand\Model\MakerFactory;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\Filter\Date;

abstract class Maker extends Action
{

    protected $makerFactory;

    protected $coreRegistry;

    protected $resultRedirectFactory;

    protected $dateFilter;

    public function __construct(
        Registry $registry,
        MakerFactory $makerFactory,
        RedirectFactory $resultRedirectFactory,
        Date $dateFilter,
        Context $context
    )
    {
        $this->coreRegistry = $registry;
        $this->makerFactory = $makerFactory;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->dateFilter = $dateFilter;
        parent::__construct($context);
    }

    /**
     * @return \Ict\Shopbybrand\Model\Maker
     */
    protected function initMaker()
    {
        $makerId  = (int) $this->getRequest()->getParam('maker_id');
        /** @var \Ict\Shopbybrand\Model\Maker $maker */
        $maker    = $this->makerFactory->create();
        if ($makerId) {
            $maker->load($makerId);
        }
        $this->coreRegistry->register('ict_shopbybrand_maker', $maker);
        return $maker;
    }

    /**
     * filter dates
     * @param array $data
     * @return array
     */
    public function filterData($data)
    {
        $inputFilter = new \Zend_Filter_Input(
            ['dob' => $this->dateFilter],
            [],
            $data
        );
        $data = $inputFilter->getUnescaped();
        if (isset($data['awards'])) {
            if (is_array($data['awards'])) {
                $data['awards'] = implode(',', $data['awards']);
            }
        }
        return $data;
    }

}
