<?php

namespace Ict\Attachments\Block;

use Magento\Framework\UrlInterface;

class ProductAttachment extends \Magento\Framework\View\Element\Template
{
	const CURRENT_PRODUCT = 'current_product';
	const IMAGE_FOLDER = 'ictAttachment/';
	const MODULE_NAME = 'Ict_Attachments';
	const IMAGE_PATH = '/frontend/web/images/';
	const UNKNOWN_IMAGE = 'Ict_Attachments::images/unknown.png';
	const IMAGE = 'Ict_Attachments::images/';
	const IMAGE_EXT = '.png';

	/**
	 * @var \Ict\Attachments\Model\ProductAttachment
	 */
	protected $collection;

	/**
	 * @var \Magento\Framework\Registry
	 */
	protected $registry;

	/**
	 * @var \Magento\Store\Model\StoreManagerInterface
	 */
	protected $storeManager;

	/**
	 * @param Ict\Attachments\Model\ProductAttachment $collection
	 * @param Magento\Framework\Registry $registry
	 * @param Magento\Store\Model\StoreManagerInterface $storeManager
	 */
	public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Ict\Attachments\Model\ProductAttachmentFactory $collection,
        \Magento\Framework\Registry $registry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Module\Dir\Reader $moduleReader,
        \Magento\Framework\Filesystem\Io\File $file,
        array $data = []
    ) {
    	$this->collection = $collection;
    	$this->registry = $registry;
    	$this->storeManager = $storeManager;
    	$this->moduleReader = $moduleReader;
    	$this->file = $file;
    	parent::__construct($context, $data);
	}

	public function getCurrentProduct()
	{
		return $this->registry->registry(self::CURRENT_PRODUCT);
	}

	public function getAttachmentCollection()
	{
		return $this->collection->create()->getCollection();
	}

	public function getMedialUrl()
	{
		return $this->storeManager->getStore()->getBaseUrl(
			UrlInterface::URL_TYPE_MEDIA
		) . self::IMAGE_FOLDER;
	}

	public function getFileIcon($fileExt)
    {
        $fileExt = \strtolower($fileExt);

        if ($fileExt) {
            $viewDir = $this->moduleReader->getModuleDir(
                \Magento\Framework\Module\Dir::MODULE_VIEW_DIR,
                self::MODULE_NAME
            );
            $imageExists = $viewDir . self::IMAGE_PATH . $fileExt . self::IMAGE_EXT;

            if (!$this->file->fileExists($imageExists)) {
                $iconImage = $this->getViewFileUrl(self::UNKNOWN_IMAGE);
            } else {
                $iconImage = $this->getViewFileUrl(self::IMAGE . $fileExt . self::IMAGE_EXT);
            }
        } else {
            $iconImage = $this->getViewFileUrl(self::UNKNOWN_IMAGE);
        }
        return $iconImage;
    }

    public function pathInformation($file, $info)
    {
        $pathInfo = $this->file->getPathInfo($file);
        return $pathInfo[$info];
    }
}