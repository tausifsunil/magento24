<?php
namespace Forever\Productlabel\Model\Config\Product;
 
class Productoption extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    public function getAllOptions()
    {
        return [
            ['value' => 1, 'label' => __('Top Left')],
            ['value' => 2, 'label' => __('Top Right')],
            ['value' => 3, 'label' => __('Top Center')],
            ['value' => 4, 'label' => __('Bottom Left')],
            ['value' => 5, 'label' => __('Bottom Right')],
            ['value' => 6, 'label' => __('Bottom Center')],
            ['value' => 7, 'label' => __('Center Left')],
            ['value' => 8, 'label' => __('Center Right')],
            ['value' => 9, 'label' => __('Center Center')],
        ];
          
    }
}