<?php

namespace Source\App\Model;

use Exception;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\MediaStorage\Helper\File\Storage\Database;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;


class ImageUploader
{
    const BASE_TMP_PATH = "source_app/product/";
    const BASE_PATH = "source_app/product/";
    const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png'];

    public $baseTmpPath;
    public $basePath;
    public $allowedExtensions;
    protected $coreFileStorageDatabase;
    protected $mediaDirectory;
    protected $uploaderFactory;
    protected $storeManager;
    protected $logger;

    /**
     * @throws FileSystemException
     */
    public function __construct(
        Database $coreFileStorageDatabase,
        Filesystem $filesystem,
        UploaderFactory $uploaderFactory,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger
    ) {
        $this->coreFileStorageDatabase = $coreFileStorageDatabase;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->uploaderFactory = $uploaderFactory;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
        $this->baseTmpPath = self::BASE_TMP_PATH;
        $this->basePath = self::BASE_PATH;
        $this->allowedExtensions = self::ALLOWED_EXTENSIONS;
    }

    public function moveFileFromTmp($imageName)
    {
        $baseTmpPath = $this->getBaseTmpPath();
        $basePath = $this->getBasePath();
        $baseImagePath = $this->getFilePath($basePath, $imageName);
        $baseTmpImagePath = $this->getFilePath($baseTmpPath, $imageName);
        try {
            $this->coreFileStorageDatabase->copyFile(
                $baseTmpImagePath,
                $baseImagePath
            );
            $this->mediaDirectory->renameFile(
                $baseTmpImagePath,
                $baseImagePath
            );
        } catch (Exception $e) {
            throw new LocalizedException(__('Something went wrong while saving the file(s).'));
        }
        return $imageName;
    }

    /**
     * @throws NoSuchEntityException
     * @throws LocalizedException
     * @throws Exception
     */
    public function saveFileToTmpDir($fileId): bool|array
    {
        $baseTmpPath = $this->getBaseTmpPath();
        $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
        $uploader->setAllowedExtensions($this->getAllowedExtensions());
        $uploader->setAllowRenameFiles(true);
        $result = $uploader->save($this->mediaDirectory->getAbsolutePath($baseTmpPath));
        if (!$result) {
            throw new LocalizedException(__('File can not be saved to the destination folder.'));
        }

        $result['tmp_name'] = str_replace('\\', '/', $result['tmp_name']);
        $result['path'] = str_replace('\\', '/', $result['path']);
        $result['url'] = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $this->getFilePath($baseTmpPath, $result['file']);
        $result['name'] = $result['file'];
        if (isset($result['file'])) {
            try {
                $relativePath = rtrim($baseTmpPath, '/') . '/' . ltrim($result['file'], '/');
                $this->coreFileStorageDatabase->saveFile($relativePath);
            } catch (Exception $e) {
                $this->logger->critical($e);
                throw new LocalizedException(__('Something went wrong while saving the file(s).'));
            }
        }
        return $result;
    }

    public function saveMediaImage($imageName, $imagePath)
    {
        $baseTmpPath = $this->getBaseTmpPath();
        $basePath = $this->getBasePath();
        $baseImagePath = $this->getFilePath($basePath, $imageName);
        $mediaPath = substr($imagePath, 0, strpos($imagePath, "media"));
        $baseTmpImagePath = str_replace($mediaPath . "media/", "", $imagePath);
        if ($baseImagePath == $baseTmpImagePath) {
            return $imageName;
        }
        try {
            $this->mediaDirectory->copyFile($baseTmpImagePath, $baseImagePath);
        } catch (Exception $e) {
            throw new LocalizedException(
                __('Something went wrong while saving the file(s).')
            );
        }
        return $imageName;
    }

    public function getBaseTmpPath(): string
    {
        return $this->baseTmpPath;
    }

    public function setBaseTmpPath($baseTmpPath): void
    {
        $this->baseTmpPath = $baseTmpPath;
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }

    public function setBasePath($basePath): void
    {
        $this->basePath = $basePath;
    }

    public function getFilePath($path, $imageName): string
    {
        return rtrim($path, '/') . '/' . ltrim($imageName, '/');
    }

    public function getAllowedExtensions(): array
    {
        return $this->allowedExtensions;
    }

    public function setAllowedExtensions($allowedExtensions): void
    {
        $this->allowedExtensions = $allowedExtensions;
    }

}
