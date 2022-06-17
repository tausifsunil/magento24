<?php

namespace Ict\Attachments\Helper;

use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Customer\Model\Context;
use Ict\Attachments\Model\Data\File as FileData;
use Magento\Framework\App\Filesystem\DirectoryList;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @param \Magento\Framework\App\Helper\Context  $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\ResponseFactory $responseFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Framework\ObjectManagerInterface $objectManager,
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryFactory
     * @param \Magento\Catalog\Model\Category $category
     * @param \Magento\Customer\Model\Url $customerUrl
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
     * @param \Magento\Backend\Model\UrlInterface $backendUrl,
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context  $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryFactory,
        \Magento\Catalog\Model\Category $category,
        \Magento\Customer\Model\Url $customerUrl,
        \Magento\Framework\Registry $registry,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Backend\Model\UrlInterface $backendUrl,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        DirectoryList $directoryList
    ) {
        parent::__construct($context);
        $this->_responseFactory = $responseFactory;
        $this->_url = $context->getUrlBuilder();
        $this->_customerSession = $customerSession;
        $this->scopeConfig = $context->getScopeConfig();
        $this->_objectManager = $objectManager;
        $this->_checkoutSession = $checkoutSession;
        $this->_transportBuilder = $transportBuilder;
        $this->_inlineTranslation = $inlineTranslation;
        $this->httpContext = $httpContext;
        $this->storeManager = $storeManager;
        $this->_categoryCollectionFactory = $categoryFactory;
        $this->_category = $category;
        $this->_coreRegistry = $registry;
        $this->_customerUrl = $customerUrl;
        $this->fileUploaderFactory = $fileUploaderFactory;
        $this->backendUrl = $backendUrl;
        $this->filesystem = $filesystem;
        $this->messageManager = $messageManager;
        $this->directoryList = $directoryList;
    }

    /**
     * Return media directory url
     *
     * @param string $path
     * @param bool $secure
     * @return string
     */
    public function getBaseUrlMedia($path = '', $secure = false)
    {
        return $this->storeManager->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA, $secure) . $path;
    }

    /**
     * @return bool
     */
    public function isLoggedIn()
    {
        return $this->httpContext->getValue(Context::CONTEXT_AUTH);
    }

    /**
     * Get store config
     *
     * @param string $config
     */
    public function getConfig($config)
    {
        return $this->scopeConfig->getValue($config, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @param string $file
     * @return string
     */
    public function getFrontpageImage($file)
    {
        return $this->storeManager->getStore()->getBaseUrl('media').'category/tmp/frontpage_image/'.$file;
    }

    /**
     * @return mixed
     */
    public function getCurrentProduct()
    {
        if (!$this->_product) {
            $this->_product = $this->_coreRegistry->registry('product');
        }
        return $this->_product;
    }

    /**
     * @param string $file
     * @return string
     */
    public function getBundlePdfUrl($file)
    {
        $url = $this->storeManager->getStore()->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        ) . FileData::BASE_PATH . $file;
        return $url;
    }

    /**
     * @param string $file
     * @return string
     */
    public function getBundlePdfPath($file)
    {
        $mediaDir = $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $url = $mediaDir . '/' . FileData::BASE_PATH . $file;
        return $url;
    }

    /**
     * @return array
     */
    public function getAllowedExt()
    {
        return ['pdf','pptx', 'xls', 'xlsx', 'flash', 'mp3', 'mp4', 'docx',
        'doc', 'zip', 'jpg', 'jpeg', 'png', 'gif', 'ini', 'readme', 'avi', 'csv', 'txt', 'wma', 'mpg', 'flv'];
    }

    /**
     * Return base url
     *
     * @return string
     */
    public function getBaseDir()
    {
        $path = $this->filesystem->getDirectoryRead(
            DirectoryList::MEDIA
        )->getAbsolutePath(\Ict\Attachments\Model\Data\File::BASE_PATH);
        return $path;
    }

    /**
     * Return url of the product grid
     *
     * @return string
     */
    public function getProductsGridUrl()
    {
        return $this->backendUrl->getUrl('attachment/index/products', ['_current' => true]);
    }

    /**
     * @param string $scope
     * @param string $model
     * @param string $fileName
     * @return mixed
     */
    public function uploadFile($scope, $model, $fileName)
    {
        try {
            $files = [];
            $uploader = $this->fileUploaderFactory->create(['fileId' => $scope]);
            $uploader->setAllowedExtensions($this->getAllowedExt());
            $uploader->setAllowRenameFiles(false);
            $uploader->setFilesDispersion(true);
            $uploader->setAllowCreateFolders(true);
            if ($result = $uploader->save($this->getBaseDir(), $fileName)) {
                if ($model->getFile()) {
                    $model->getFile();
                    $files = json_decode($model->getFile(), true);
                }
                array_push($files, $uploader->getUploadedFileName());
                $model->setFile(json_encode($files));
            }
            return $result;
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }

        return $model;
    }

    /**
     * @return string
     */
    public function getAttachmentBaseUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        ) . \Ict\Attachments\Model\Data\File::BASE_PATH;
    }
}
