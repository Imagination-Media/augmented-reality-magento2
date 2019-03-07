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

namespace ImaginationMedia\Aero\Model;

use ImaginationMedia\Aero\Api\Data\ArInterface;
use ImaginationMedia\Aero\Model\ResourceModel\AugmentedReality as ResourceModel;
use Magento\Framework\Model\AbstractModel;

class AugmentedReality extends AbstractModel implements ArInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * Get product id
     * @api
     * @return int
     */
    public function getProductId() : int
    {
        return (int)$this->getData("product_id");
    }

    /**
     * Set product id
     * @api
     * @param int $productId
     */
    public function setProductId(int $productId)
    {
        $this->setData("product_id", $productId);
    }

    /**
     * Get preview image file path
     * @api
     * @return string
     */
    public function getPreviewPath() : string
    {
        return (string)$this->getData("preview_path");
    }

    /**
     * Set preview image file path
     * @api
     * @param string $path
     */
    public function setPreviewPath(string $path)
    {
        $this->setData("preview_path", $path);
    }

    /**
     * Get AR file path
     * @api
     * @return string
     */
    public function getArPath() : string
    {
        return (string)$this->getData("ar_path");
    }

    /**
     * Set AR file path
     * @api
     * @param string $path
     */
    public function setArPath(string $path)
    {
        $this->setData("ar_path", $path);
    }
}
