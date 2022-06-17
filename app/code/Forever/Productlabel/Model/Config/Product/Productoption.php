<?php
namespace Forever\Productlabel\Model\Config\Product;
 
class Productoption extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    public function getAllOptions()
    {
        return [
            ['value' => 1, 'label' => __('Top Left')],
            ['value' => 2, 'label' => __('Top Right')],
        ];
    }
}
