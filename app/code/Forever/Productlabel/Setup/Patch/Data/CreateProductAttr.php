<?php

declare(strict_types=1);

namespace Forever\Productlabel\Setup\Patch\Data;

use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class CreateProductAttr implements DataPatchInterface
{

    /**
     * ModuleDataSetupInterface
     *
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * EavSetupFactory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory          $eavSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $eavSetup->addAttribute('catalog_product', 'productlabel', [
            'group' => 'Product Label',
            'label' => 'Multiselect Attribute',
            'type' => 'text',
            'input' => 'multiselect',
            'source' => \Forever\Productlabel\Model\Config\Product\Productmultisel::class,
            'required' => false,
            'sort_order' => 30,
            'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE,
            'used_in_product_listing' => true,
            'backend' => \Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend::class,
            'visible_on_front' => false
        ]);

         $eavSetup->addAttribute('catalog_product', 'productoption', [
            'group' => 'Product Label',
            'label' => 'Product Position',
            'type' => 'varchar',
            'input' => 'select',
            'required' => false,
            'sort_order' => 40,
            'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE,
            'used_in_product_listing' => true,
            'source' => \Forever\Productlabel\Model\Config\Product\Productoption::class,
            'visible_on_front' => false
         ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}
