<?php

namespace Ict\Attachments\Model\Attribute\Backend\Product;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\RequestInterface;
use Ict\Attachments\Model\Data\File as FileData;

class File extends \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
{
    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $_filesystem;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * @var \Ict\Attachment\Helper\File
     */
    protected $_fileHelper;

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Framework\Filesystem\Driver\File $fileDriver
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Filesystem\Io\File $file
     * @param \Magento\Framework\HTTP\PhpEnvironment\Request $httpRequest
     */
    public function __construct(
        RequestInterface $request,
        \Ict\Attachments\Helper\File $fileHelper,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\Filesystem\Driver\File $fileDriver,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Filesystem\Io\File $file,
        \Magento\Framework\HTTP\PhpEnvironment\Request $httpRequest
    ) {
        $this->request = $request;
        $this->_filesystem = $filesystem;
        $this->_logger = $logger;
        $this->_fileHelper = $fileHelper;
        $this->productFactory = $productFactory;
        $this->fileDriver = $fileDriver;
        $this->registry = $registry;
        $this->file = $file;
        $this->httpRequest = $httpRequest;
    }

    /**
     * @param object $object
     */
    public function beforeSave($object)
    {
        $product = $this->productFactory->create()->load($object->getId());
        $postProduct = $this->request->getPost('product');
        // echo'<pre>';print_r($postProduct);die();
        $value = $object->getData($this->getAttribute()->getName());
        $attributeValue = $product->getResource()
            ->getAttribute($this->getAttribute()->getName())->getFrontend()->getValue($product);
        $file = [];

        if (!empty($attributeValue)) {
            $file = json_decode($attributeValue, true);
        }

        if (!isset($postProduct[$this->getAttribute()->getName()]) && $value) {
            $object->setData($this->getAttribute()->getName(), '');
            if (!empty($file)) {
                foreach ($file as $key => $filePath) {
                    $mediaDirectory = $this->_filesystem
                        ->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
                    $fileRootDir = $mediaDirectory->getAbsolutePath().\Ict\Attachments\Model\Data\File::BASE_PATH;
                    if ($this->fileDriver->isExists($fileRootDir.$filePath)) {
                        $this->fileDriver->deleteFile($fileRootDir.$filePath);
                    }
                }
            }
            return $this;
        }
        $files = $this->request->getFiles()->toArray();
        if (empty($value) && empty($files)) {
            return $this;
        }
        // $postValue = $this->request->getPost('product')[$this->getAttribute()->getName()];
        try {
            $result = $files;

            if (!empty($file)) {
                $postFiles = array_column($result, 'name');
                foreach ($file as $key => $filePath) {
                    $pathInfo = $this->file->getPathInfo($filePath);
                    $name = $pathInfo['basename'];
                    if (!in_array($name, $postFiles)) {
                        $mediaDirectory = $this->_filesystem
                            ->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
                        $fileRootDir = $mediaDirectory->getAbsolutePath().\Ict\Attachments\Model\Data\File::BASE_PATH;
                        if ($this->fileDriver->isExists($fileRootDir.$filePath)) {
                            $this->fileDriver->deleteFile($fileRootDir.$filePath);
                            unset($file[$key]);
                        }
                    }
                }
            }
            foreach ($result as $files) {
                if (isset($files['file'])) {
                    $fileName = $files['file'];
                    if ($product->getId() === null) {
                        $append = $object->getSku();
                    } else {
                        $append = $product->getId();
                    }
                    $pathInfo = $this->file->getPathInfo($fileName);
                    $newFileName = str_replace(
                        $pathInfo['filename'],
                        $pathInfo['filename'].'_'.$append,
                        $pathInfo['basename']
                    );
                    $result = $this->_fileHelper
                        ->moveFileFromTmp(FileData::BASE_TMP_PATH, FileData::BASE_PATH, $fileName, $newFileName);
                    array_push($file, $result);
                }
            }
            $object->setData($this->getAttribute()->getName(), json_encode($file));

        } catch (\Exception $e) {
            if ($e->getCode() != \Magento\MediaStorage\Model\File\Uploader::TMP_NAME_EMPTY) {
                $this->_logger->critical($e);
            }
        }
        return $this;
    }
}
