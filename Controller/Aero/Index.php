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

namespace ImaginationMedia\Aero\Controller\Aero;

use ImaginationMedia\Aero\Model\AugmentedRealityRepository;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

class Index extends Action
{
    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var AugmentedRealityRepository
     */
    private $augmentedRealityRepository;

    /**
     * Index constructor.
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param AugmentedRealityRepository $augmentedRealityRepository
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        AugmentedRealityRepository $augmentedRealityRepository,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->augmentedRealityRepository = $augmentedRealityRepository;
        $this->storeManager = $storeManager;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        $result = $this->jsonFactory->create();
        $data = $this->getRequest()->getParams();
        if (key_exists("product_id", $data) && (string)$data["product_id"] !== "") {
            $arData = $this->augmentedRealityRepository->getArByProduct($data["product_id"]);
            if (key_exists("ar_path", $arData) &&
                (string)$arData["ar_path"] !== "") {
                $arPath = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA)
                    . "catalog/product/ar/" . $arData["ar_path"];
                $arPreview = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA)
                    . "catalog/product/ar/" . $arData["preview_path"];
                $resultData = [
                    "has_ar" => 1,
                    "ar" => $arPath,
                    "preview" => $arPreview
                ];
                $result->setData($resultData);
            } else {
                $result->setData(['has_ar' => 0]);
            }
        } else {
            $result->setData(['has_ar' => 0]);
        }
        return $result;
    }
}
