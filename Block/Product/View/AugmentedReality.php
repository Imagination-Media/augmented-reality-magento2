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
     * Get controller url
     * @return string
     */
    public function getPostUrl() : string
    {
        return $this->getUrl("aero/aero/index");
    }

    /**
     * Get current product id
     * @return int
     */
    public function getProductId() : int
    {
        return (int)$this->getProduct()->getId();
    }
}
