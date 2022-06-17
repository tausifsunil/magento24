<?php
/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_Quickorder
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */
 
namespace Ict\Quickorder\Controller\Index;

use Magento\Catalog\Api\ProductRepositoryInterface;

class Addtocart extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    protected $resultPageFactory;
    
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    protected $resultJsonFactory;
    
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     */
    protected $productRepository;
    
    /**
     * @var \Magento\Checkout\Model\Cart $cart
     */
    protected $cart;
    
    /**
     * @var \Magento\Checkout\Helper\Cart $cartHelper
     */
    protected $cartHelper;
    
    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Checkout\Model\Cart $cart
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        ProductRepositoryInterface $productRepository,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Checkout\Helper\Cart $cartHelper,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        array $data = []
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->checkoutSession = $checkoutSession;
        $this->productRepository = $productRepository;
        $this->_cart = $cart;
        $this->_cartHelper = $cartHelper;
        $this->productFactory = $productFactory;
        parent::__construct($context);
    }
    
    /**
     * Product add to cart
     */
    public function execute()
    {
        $prod_data = json_decode($this->getRequest()->getPost('myarray'), true);
        $prod_option = [];
        if (!empty($prod_data)) {
            $orderArr = [];
            if (!empty($prod_data)) {
                try {
                    $this->checkoutSession->setQuickAddTocart(true);
                    foreach ($prod_data as $key => $arrData) {
                        if (isset($arrData['options'])) {
                            // here start
                            // $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                            // $productId = $arrData['product_id'];
                            // $product = $objectManager->create('Magento\Catalog\Model\ProductFactory')->create()->load($productId);
                            // // $product = $this->productRepository->getById($arrData['product_id']);
                            // $productAttributeOptions = $product->getTypeInstance(true)->getConfigurableAttributesAsArray($product);

                            // foreach($productAttributeOptions as $option){
                            //     $options[$option['attribute_id']] =  $childProduct->getData($option['attribute_code']);
                            // }
                            // $params['super_attribute'] = $options;
                            // echo "<pre>mahen";print_r($params);die();
                            // $this->_cart->addProduct($parent, $params);
                            // $this->_cart->save();
                            // here end

                            

                            $opt=[];
                            foreach($arrData['options'] as $val){
                                $opt[$val['perent_id']] = $val['option_id'];
                            }
                            if(!empty($opt)) {
                                $orderArr = [
                                    'product' => $arrData['product_id'],
                                    'super_attribute' => $opt,
                                    'qty' => $arrData['qty'],
                                ];
                            }
                        } else {
                            $orderArr = [
                                'product' => $arrData['product_id'],
                                'qty' => $arrData['qty']
                            ];
                        }
                        $_product = $this->productRepository->getById($arrData['product_id']);
                        $data = $this->_cartHelper->getAddUrl($_product, $orderArr);
                        /*echo '<pre>';
                        print_r($data);
                        die;*/
                        $this->_cart->addProduct($_product, $orderArr);
                        // echo "<pre>";print_r(get_class_methods($this->_cart->addProduct()));
                    }
                        // die();
                    
                    $this->_cart->save();
                    $this->checkoutSession->setQuickAddTocart(false);
                    $this->checkoutSession->setCartWasUpdated(true);
                    $this->messageManager->addSuccess(__('Product added to cart successfully'));
                    return false;
                } catch (\Exception $e) {
                    $this->messageManager->addError(__($e->getMessage()));
                    return false;
                }
            } else {
                $this->messageManager->addError(__('Please select product options'));
                return false;
            }
        } else {
            $this->messageManager->addError(__('Please add proper product information'));
            return false;
        }
    }
}
