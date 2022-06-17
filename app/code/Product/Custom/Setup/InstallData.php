<?php
namespace Product\Custom\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    private $eavSetupFactory;

    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'productswitch', [
                'type' => 'int',
                'label' => 'Product Switch Attributes',
                'input' => 'boolean',
                'source' => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
                'visible' => true,
                'default' => '0',
                'required' => false,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Custom Field',
            ]);
            $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'producttext', [
                    'group' => 'Custom Field',
                    'type' => 'varchar',
                    'label' => 'Product Text Attributes',
                    'input' => 'text',
                    'source' => '',
                    'frontend' => '',
                    'backend' => '',
                    'required' => false,
                    'sort_order' => 50,
                    'is_used_in_grid' => false,
                    'is_visible_in_grid' => false,
                    'is_filterable_in_grid' => false,
                    'visible' => true,
                    'is_html_allowed_on_front' => true,
                    'visible_on_front' => false
                ]);
            $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'productselect', [
                'type' => 'int',
                'label' => 'Product Select Attribute',
                'input' => 'select',
                'sort_order' => 100,
                'source' => \Product\Custom\Model\Config\Product\Select::class,
                'global' => 2,
                'visible' => true,
                'required' => true,
                'user_defined' => false,
                'default' => null,
                'group' => 'Custom Field',
                'backend' => ''
            ]);
            $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'productmultiselect', [
                'group' => 'Custom Field',
                'label' => 'Product Multiselect Attribute',
                'type'=> 'varchar',
                'input' => 'multiselect',
                'source' => \Product\Custom\Model\Config\Product\Multiselect::class,
                'required' => false,
                'sort_order' => 30,
                'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE,
                'used_in_product_listing' => true,
                'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend::class',
                'visible_on_front' => false
            ]);
            $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'productwidget', [
                'type' => 'text',
                'label' => 'Product Widget Attribute',
                'input' => 'textarea',
                'required' => false,
                'sort_order' => 102,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'wysiwyg_enabled' => true,
                'is_html_allowed_on_front' => true,
                'group' => 'Custom Field'
            ]);
            $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'productfeatureswidget', [
                'type' => 'text',
                'label' => 'Product Features Widget Attribute',
                'input' => 'textarea',
                'required' => false,
                'sort_order' => 102,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'wysiwyg_enabled' => true,
                'is_html_allowed_on_front' => true,
                'group' => 'Custom Field'
            ]);
    }
}
