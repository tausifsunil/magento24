<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Created By: MageDelight Pvt. Ltd.
 */
namespace Grid\Slider\Setup;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        /**
         * Create table 'grid_Slider'
         */
        if (!$installer->tableExists('grid_slider')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('grid_slider')
            )->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true,
                    'unsigned' => true,
                ],
                'ID'
            )->addColumn(
                'name',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable => false',
                ],
                'Name'
            )->addColumn(
                'position',
                Table::TYPE_INTEGER,
                '255',
                [
                    'nullable => false',
                ],
                'position'
            )->addColumn(
                'status',
                Table::TYPE_SMALLINT,
                null,
                [
                    'nullable' => false,
                ],
                'Status'
            )->addColumn(
                'blog_id',
                Table::TYPE_TEXT,
                '255',
                [
                    'nullable => false',
                ],
                'blog_id'
            )->setComment('Slider Table');
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}