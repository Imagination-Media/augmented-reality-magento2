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

namespace ImaginationMedia\Aero\Api\Data;

interface ArInterface
{
    /**
     * Get product id
     * @api
     * @return int
     */
    public function getProductId() : int;

    /**
     * Set product id
     * @api
     * @param int $productId
     */
    public function setProductId(int $productId);

    /**
     * Get preview image file path
     * @api
     * @return string
     */
    public function getPreviewPath() : string;

    /**
     * Set preview image file path
     * @api
     * @param string $path
     */
    public function setPreviewPath(string $path);

    /**
     * Get AR file path
     * @api
     * @return string
     */
    public function getArPath() : string;

    /**
     * Set AR file path
     * @api
     * @param string $path
     */
    public function setArPath(string $path);
}
