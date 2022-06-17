<?php

/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_RMA
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */

namespace Ict\RMA\Block\Index;

class View extends \Magento\Framework\View\Element\Template
{
    /**
     * Customer  Mail
     */
    public const CUSTOMER_EMAIL = 'customer_email';
    
    /**
     * Rma Id
     */
    public const RMA_ID = 'rma_id';

    /**
     * Asc Sort
     */
    public const ASC_SORT = 'ASC';

    /**
     * Refund Label
     */
    public const REFUND_LABEL = 'Refund';

    /**
     * Repalcement Label
     */
    public const REPLACEMENT_LABEL = 'Replacement';

    /**
     * @var \Ict\RMA\Block\Index\Index
     */
    protected $block;

    /**
     * @var \Ict\RMA\Model\RmaPackageCondition
     */
    protected $rmapackage;

    /**
     * @var \Ict\RMA\Model\RmaStatus
     */
    protected $rmastatus;

    /**
     * @var \Ict\RMA\Model\RmaFactory
     */
    protected $rmaFactory;

    /**
     * @param Ict\RMA\Block\Index\Index $block
     * @param Ict\RMA\Model\RmaPackageCondition $rmapackage
     * @param Ict\RMA\Model\RmaStatus $rmastatus
     * @param Ict\RMA\Model\RmaFactory $rmaFactory
     * @param Magento\Catalog\Block\Product\Context $context
     * @param array $data
     */
    public function __construct(
        \Ict\RMA\Block\Index\Index $block,
        \Ict\RMA\Model\RmaPackageCondition $rmapackage,
        \Ict\RMA\Model\RmaStatus $rmastatus,
        \Ict\RMA\Model\RmaFactory $rmaFactory,
        \Magento\Catalog\Block\Product\Context $context,
        array $data = []
    ) {
        $this->block = $block;
        $this->rmapackage = $rmapackage;
        $this->rmastatus = $rmastatus;
        $this->rmaFactory = $rmaFactory;
        parent::__construct($context, $data);
    }

    /**
     * Get Rma Collection
     *
     * @return RMA Collection
     */
    public function getRmaCollection()
    {
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : 10;
        $rmaCollection = $this->rmaFactory->create()->getCollection()
            ->addFieldToFilter(self::CUSTOMER_EMAIL, $this->block->getCustomerEmail());
        $rmaCollection->setOrder(self::RMA_ID, self::ASC_SORT);
        $rmaCollection->setPageSize($pageSize);
        $rmaCollection->setCurPage($page);
        return $rmaCollection;
    }

    /**
     * Prepare Layout
     *
     * @return Layout
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->pageConfig->getTitle()->set(__('RMA'));

        if ($this->getRmaCollection()) {
            $pager = $this->getLayout()->createBlock(
                \Magento\Theme\Block\Html\Pager::class,
                'ict.rma.pager'
            )->setAvailableLimit([10 => 10, 20 => 20, 30 => 30])->setShowPerPage(true)->setCollection(
                $this->getRmaCollection()
            );
            $this->setChild('pager', $pager);
            $this->getRmaCollection()->load();
        }
        return $this;
    }

    /**
     * Get Pager Html
     *
     * @return Pager
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * Get Resolution
     *
     * @param numeric $resolution
     * @return RMA Resolution
     */
    public function getResolution($resolution)
    {
        if ($resolution == 1) {
            return self::REFUND_LABEL;
        } elseif ($resolution == 2) {
            return self::REPLACEMENT_LABEL;
        }
    }

    /**
     * Get Package Condition
     *
     * @param numeric $packageid
     * @return RMA PackageCondition
     */
    public function getPackageCondition($packageid)
    {
        $_collection = $this->rmapackage->load($packageid);
        return $_collection->getName();
    }

    /**
     * Get Statuses
     *
     * @param Numeric $statusid
     * @return RMA Status
     */
    public function getStatuses($statusid)
    {
        $_collection = $this->rmastatus->load($statusid);
        return $_collection->getStatuses();
    }
}
