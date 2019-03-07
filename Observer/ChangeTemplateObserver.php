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

namespace ImaginationMedia\Aero\Observer;

use Magento\ProductVideo\Observer\ChangeTemplateObserver as ProductVideoObserver;

class ChangeTemplateObserver extends ProductVideoObserver
{
    /**
     * Set the Aero gallery.phtml template
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $observer->getBlock()->setTemplate('ImaginationMedia_Aero::helper/gallery.phtml');
    }
}
