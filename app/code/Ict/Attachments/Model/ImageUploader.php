<?php
namespace Ict\Attachments\Model;
 
/**
 * Feature image uploader
 */
class ImageUploader
{
    const IMAGE_BASE_PATH = 'ictAttachment';

    /**
     * Core file storage database
     *
     * @var Database
     */
    protected $coreFileStorageDatabase;
 
    /**
     * Media directory object (writable).
     *
     * @var WriteInterface
     */
    protected $mediaDirectory;
 
    /**
     * Uploader factory
     *
     * @var UploaderFactory
     */
    protected $uploaderFactory;
 
    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $storeManager;
 
    /**
     * @var LoggerInterface
     */
    protected $logger;
 
    /**
     * Base path
     *
     * @var string
     */
    protected $basePath;
 
    /**
     * Allowed extensions
     *
     * @var string
     */
    protected $allowedExtensions;
 
    /**
     * ImageUploader constructor
     *
     * @param Database $coreFileStorageDatabase
     * @param Filesystem $filesystem
     * @param UploaderFactory $uploaderFactory
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface $logger
     * @param string $basePath
     * @param string[] $allowedExtensions
     * @param string[] $allowedMimeTypes
     * @throws FileSystemException
     */
    public function __construct(
        \Magento\MediaStorage\Helper\File\Storage\Database $coreFileStorageDatabase,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Psr\Log\LoggerInterface $logger,
        $basePath = self::IMAGE_BASE_PATH,
        $allowedExtensions = []
    ) {
        $this->coreFileStorageDatabase = $coreFileStorageDatabase;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->uploaderFactory = $uploaderFactory;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
        $this->basePath = $basePath;
        $this->allowedExtensions = $allowedExtensions;
    }

    /**
     * Set base path
     *
     * @param string $basePath
     *
     * @return void
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * Retrieve base path
     *
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * Set allowed extensions
     *
     * @param string[] $allowedExtensions
     *
     * @return void
     */
    public function setAllowedExtensions($allowedExtensions)
    {
        $this->allowedExtensions = $allowedExtensions;
    }
 
    /**
     * Retrieve allowed extensions
     *
     * @return string[]
     */
    public function getAllowedExtensions()
    {
        return $this->allowedExtensions;
    }
 
    /**
     * Retrieve path
     *
     * @param string $path
     * @param string $imageName
     *
     * @return string
     */
    public function getFilePath($path, $imageName)
    {
        return rtrim($path, '/') . '/' . ltrim($imageName, '/');
    }
 
    /**
     * Checking file for moving and move it
     *
     * @param string $imageName
     *
     * @return string
     *
     * @throws LocalizedException
     */
    // public function moveFileFromTmp($imageName)
    // {
    //     $baseTmpPath = $this->getBaseTmpPath();
    //     $basePath = $this->getBasePath();
 
    //     $baseImagePath = $this->getFilePath(
    //         $basePath,
    //         Uploader::getNewFileName(
    //             $this->mediaDirectory->getAbsolutePath(
    //                 $this->getFilePath($basePath, $imageName)
    //             )
    //         )
    //     );
    //     $baseTmpImagePath = $this->getFilePath($baseTmpPath, $imageName);
 
    //     try {
    //         $this->coreFileStorageDatabase->copyFile(
    //             $baseTmpImagePath,
    //             $baseImagePath
    //         );
    //         $this->mediaDirectory->renameFile(
    //             $baseTmpImagePath,
    //             $baseImagePath
    //         );
    //     } catch (Exception $e) {
    //         throw new LocalizedException(
    //             __('Something went wrong while saving the file(s).')
    //         );
    //     }
 
    //     return $imageName;
    // }
 
    /**
     * Checking file for save and save it to tmp dir
     *
     * @param string $fileId
     *
     * @return string[]
     *
     * @throws LocalizedException
     */
    public function saveFileToTmpDir($fileId)
    {
        $basePath = $this->getBasePath();
        $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
        $uploader->setAllowedExtensions($this->getAllowedExtensions());
        $uploader->setAllowRenameFiles(true);
        $result = $uploader->save($this->mediaDirectory->getAbsolutePath($basePath));
        if (!$result) {
            throw new LocalizedException(
                __('File can not be saved to the destination folder.')
            );
        }
 
        /**
         * Workaround for prototype 1.7 methods "isJSON", "evalJSON" on Windows OS
         */
        $result['tmp_name'] = str_replace('\\', '/', $result['tmp_name']);
        $result['path'] = str_replace('\\', '/', $result['path']);
        $result['url'] = $this->storeManager
                ->getStore()
                ->getBaseUrl(
                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                ) . $this->getFilePath($basePath, $result['file']);
        $result['name'] = $result['file'];
 
        if (isset($result['file'])) {
            try {
                $relativePath = rtrim($basePath, '/') . '/' . ltrim($result['file'], '/');
                $this->coreFileStorageDatabase->saveFile($relativePath);
            } catch (Exception $e) {
                $this->logger->critical($e);
                throw new LocalizedException(
                    __('Something went wrong while saving the file(s).')
                );
            }
        }
 
        return $result;
    }
}