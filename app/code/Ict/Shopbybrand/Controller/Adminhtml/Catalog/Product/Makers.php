<?php
/**
 * @author Ict Team
 * @copyright Copyright (c) 2017 Ict (http://icreativetechnologies.com/)
 * @package Ict_Shopbybrand
 */

namespace Ict\Shopbybrand\Controller\Adminhtml\Catalog\Product;

use Magento\Catalog\Controller\Adminhtml\Product\Edit;

class Makers extends Edit
{
 
    protected $resultLayoutFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Catalog\Controller\Adminhtml\Product\Builder $productBuilder,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        // \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
    )
    {
        parent::__construct($context, $productBuilder, $resultPageFactory);
        $this->resultLayoutFactory = $resultLayoutFactory;
    }

    public function execute()
    {
        $productId = (int) $this->getRequest()->getParam('id');
        $product = $this->productBuilder->build($this->getRequest());

        if ($productId && !$product->getId()) {
            $this->messageManager->addError(__('This product no longer exists.'));
            /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('catalog/*/');
        }
        $resultLayout = $this->resultLayoutFactory->create();
        /** @var \Ict\Shopbybrand\Block\Adminhtml\Catalog\Product\Edit\Tab\Maker $makersBlock */
        $makersBlock = $resultLayout->getLayout()->getBlock('ict_shopbybrand.maker');
        if ($makersBlock) {
            $makersBlock->setProductMakers($this->getRequest()->getPost('product_makers', null));
        }
        return $resultLayout;
    }
}
