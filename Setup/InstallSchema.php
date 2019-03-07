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

namespace ImaginationMedia\Aero\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{

    const AR_TABLE = "catalog_product_entity_augmented_reality";

    /**
     * @var File
     */
    private $file;

    /**
     * @var DirectoryList
     */
    private $directoryList;

    /**
     * InstallSchema constructor.
     * @param File $file
     * @param DirectoryList $directoryList
     */
    public function __construct(
        File $file,
        DirectoryList $directoryList
    ) {
        $this->file = $file;
        $this->directoryList = $directoryList;
    }

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        /**
         * Create AR database table
         */
        $setup->startSetup();

        $newTable = $setup->getConnection()->newTable($setup->getTable(self::AR_TABLE))
        ->addColumn(
            'id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Id'
        )
        ->addColumn(
            'product_id',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Product Id'
        )
        ->addColumn(
            'preview_path',
            Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'AR Preview file path'
        )
        ->addColumn(
            'ar_path',
            Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'Usdz file path'
        )
        ->addForeignKey(
            $setup->getFkName(self::AR_TABLE, 'product_id', 'catalog_product_entity', 'entity_id'),
            'product_id',
            $setup->getTable('catalog_product_entity'),
            'entity_id',
            Table::ACTION_CASCADE
        )
        ->setComment('Magento 2 Integration with Adobe Aero and Apple ArKit');

        $setup->getConnection()->createTable($newTable);

        $setup->endSetup();

        /**
         * Create AR media folder
         */
        $this->file->mkdir($this->directoryList->getPath('media').'/catalog/product/ar', 0775);
    }
}
