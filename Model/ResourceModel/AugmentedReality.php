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

namespace ImaginationMedia\Aero\Model\ResourceModel;

use ImaginationMedia\Aero\Setup\InstallSchema;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class AugmentedReality extends AbstractDb
{
    protected function _construct()
    {
        $this->_init(InstallSchema::AR_TABLE, 'id');
    }
}
