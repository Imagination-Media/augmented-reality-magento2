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

namespace ImaginationMedia\Aero\Block\Product\View;

use ImaginationMedia\Aero\Model\AugmentedRealityRepository;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Block\Product\View;
use Magento\Catalog\Helper\Product as ProductHelper;
use Magento\Catalog\Model\ProductTypes\ConfigInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Json\EncoderInterface as JsonEnconderInterface;
use Magento\Framework\Locale\FormatInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Framework\Url\EncoderInterface;
use Magento\Framework\UrlInterface;

class AugmentedReality extends View
{
    /**
     * @var
     */
    private $augmentedRealityRepo;

    /**
     * AugmentedReality constructor.
     * @param Context $context
     * @param EncoderInterface $urlEncoder
     * @param JsonEnconderInterface $jsonEncoder
     * @param StringUtils $string
     * @param ProductHelper $productHelper
     * @param ConfigInterface $productTypeConfig
     * @param FormatInterface $localeFormat
     * @param Session $customerSession
     * @param ProductRepositoryInterface $productRepository
     * @param PriceCurrencyInterface $priceCurrency
     * @param AugmentedRealityRepository $augmentedRealityRepo
     * @param array $data
     */
    public function __construct(
        Context $context,
        EncoderInterface $urlEncoder,
        JsonEnconderInterface $jsonEncoder,
        StringUtils $string,
        ProductHelper $productHelper,
        ConfigInterface $productTypeConfig,
        FormatInterface $localeFormat,
        Session $customerSession,
        ProductRepositoryInterface $productRepository,
        PriceCurrencyInterface $priceCurrency,
        AugmentedRealityRepository $augmentedRealityRepo,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepository,
            $priceCurrency,
            $data
        );
        $this->augmentedRealityRepo = $augmentedRealityRepo;
    }

    /**
     * Check if there is a augmented reality file and preview
     * @return bool
     */
    public function hasAugmentedFile() : bool
    {
        $productId = $this->getProduct()->getId();
        return (count($this->augmentedRealityRepo->getArByProduct($productId)) > 0);
    }

    /**
     * Get preview path
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getPreviewArUrl() : string
    {
        $productId = $this->getProduct()->getId();
        $arData = $this->augmentedRealityRepo->getArByProduct($productId);
        if (key_exists("preview_path", $arData) &&
            (string)$arData["preview_path"] !== "") {
            return $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . "catalog/product/ar/" .
                $arData["preview_path"];
        }
        return "";
    }

    /**
     * Get AR file path
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getArFileUrl() : string
    {
        $productId = $this->getProduct()->getId();
        $arData = $this->augmentedRealityRepo->getArByProduct($productId);
        if (key_exists("ar_path", $arData) &&
            (string)$arData["ar_path"] !== "") {
            return $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . "catalog/product/ar/" .
                $arData["ar_path"];
        }
        return "";
    }
}
