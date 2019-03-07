<?php

/**
 * Aero
 *
 * Add augmented reality to Magento using Adobe Aero and Apple ArKit.
 *
 * @package ImaginationMedia\Aero
 * @author Igor Ludgero Miura <igor@imaginationmedia.com>
 * @copyright Copyright (c) 2019 Imagination Media (https://www.imaginationmedia.com/)
 * @license https://opensource.org/licenses/OSL-3.0.php Open Software License 3.0
 */

namespace ImaginationMedia\Aero\Controller\Adminhtml\Media;

use ImaginationMedia\Aero\Api\ArRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\UrlInterface;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;

class Upload extends Action
{
    const ADMIN_RESOURCE = "Magento_Catalog::products";

    /**
     * @var Filesystem
     */
    private $fileSystem;

    /**
     * @var UploaderFactory
     */
    private $uploaderFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    /**
     * @var ArRepositoryInterface
     */
    private $arRepository;

    /**
     * Upload constructor.
     * @param Action\Context $context
     * @param Filesystem $filesystem
     * @param UploaderFactory $uploaderFactory
     * @param StoreManagerInterface $storeManager
     * @param JsonFactory $jsonFactory
     * @param ArRepositoryInterface $arRepository
     */
    public function __construct(
        Action\Context $context,
        Filesystem $filesystem,
        UploaderFactory $uploaderFactory,
        StoreManagerInterface $storeManager,
        JsonFactory $jsonFactory,
        ArRepositoryInterface $arRepository
    ) {
        parent::__construct($context);
        $this->fileSystem = $filesystem;
        $this->uploaderFactory = $uploaderFactory;
        $this->storeManager = $storeManager;
        $this->jsonFactory = $jsonFactory;
        $this->arRepository = $arRepository;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     * @throws \Exception
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $files = $_FILES;
        $arFileName = "";
        $arPreviewFileName = "";
        $target = $this->fileSystem->getDirectoryRead(DirectoryList::MEDIA)
                ->getAbsolutePath() . "catalog/product/ar/";
        $results = [];
        try {
            if (key_exists("ar_file", $files)) {
                /**
                 * Upload AR file
                 */
                /** @var $uploader \Magento\MediaStorage\Model\File\Uploader */
                $uploader = $this->uploaderFactory->create(['fileId' => 'ar_file']);
                $uploader->setAllowedExtensions(['usdz']);
                $uploader->setAllowRenameFiles(true);
                $result = $uploader->save($target);
                if ($result["error"] === 0) {
                    $arFileName = $result["name"];
                    $results[] = [
                        "type" => "ar_file",
                        "url" => $this->storeManager->getStore()
                                ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . "catalog/product/ar/" . $arFileName
                    ];
                }
            }
            if (key_exists("ar_preview_file", $files)) {
                /**
                 * Upload AR preview image
                 */
                /** @var $uploader \Magento\MediaStorage\Model\File\Uploader */
                $uploader = $this->uploaderFactory->create(['fileId' => 'ar_preview_file']);
                $uploader->setAllowedExtensions(['png', 'bmp', 'jpg', 'jpeg', 'gif']);
                $uploader->setAllowRenameFiles(true);
                $result = $uploader->save($target);
                if ($result["error"] === 0) {
                    $arPreviewFileName = $result["name"];
                    $results[] = [
                        "type" => "ar_preview",
                        "url" => $this->storeManager->getStore()
                                ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . "catalog/product/ar/" . $arPreviewFileName
                    ];
                }
            }

            $arData = $this->arRepository->getArByProduct((int)$data["product_id"]);
            if (count($arData) === 0 && ($arFileName === "" || $arPreviewFileName === "")) {
                return $this->jsonFactory->create()->setData(['Status' => "Error", "Message" => "Invalid post data!"]);
            } else {
                $arItem = $this->arRepository->getEmptyItem();
                $arItem->setData($arData);
                if ($arFileName !== "") {
                    $arItem->setArPath($arFileName);
                }
                if ($arPreviewFileName !== "") {
                    $arItem->setPreviewPath($arPreviewFileName);
                }
                $arItem->setProductId($data["product_id"]);
                $this->arRepository->save($arItem);
                return $this->jsonFactory->create()->setData(['Status' => "Success", 'Items' => $results]);
            }
        } catch (\Exception $ex) {
            return $this->jsonFactory->create()->setData(['Status' => "Error", "Message" => $ex->getMessage()]);
        }
    }
}
