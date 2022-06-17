<?php

namespace BkFootware\Newsletter\Setup\Patch\Schema;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\App\ResourceConnection;

class AddColumn implements SchemaPatchInterface
{
    /**
     * setup moduledatasetupinterface
     *
     * @var \Magento\Framework\Setup\ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * app resourceconnection
     *
     * @var Magento\Framework\App\ResourceConnection
     */
    protected $resource;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param ResourceConnection $resource
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        ResourceConnection $resource
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->resource = $resource;
    }

    /**
     * @return array
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @return void
     */
    public function apply()
    {
        $connection = $this->resource->getConnection();
        $tableName = $connection->getTableName('newsletter_subscriber');
        $this->moduleDataSetup->startSetup();
        $this->moduleDataSetup->getConnection()->addColumn(
            $this->moduleDataSetup->getTable($tableName),
            'name',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'comment'  => 'Subscriber Name',
            ]
        );
        $this->moduleDataSetup->endSetup();
    }
}
