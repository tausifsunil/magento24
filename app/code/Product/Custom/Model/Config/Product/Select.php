<?php
namespace Product\Custom\Model\Config\Product;

// use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
// use Magento\Eav\Model\Entity\Attribute\Source\SourceInterface;
// use Magento\Framework\Option\ArrayInterface;

class Select extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    public function getAllOptions()
    {
        return [
            ['value' => 1, 'label' => __('Apple')],
            ['value' => 2, 'label' => __('Banana')],
            ['value' => 3, 'label' => __('Graps')],
            ['value' => 4, 'label' => __('Watermelon')]
        ];
    }
}
