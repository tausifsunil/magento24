<?php

namespace Grid\Slider\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class Pagelist extends AbstractSource
{
    public function getAllOptions()
    {
        return [
            ['value' => '0', 'label' => __('Home Page')],
            ['value' => '1', 'label' => __('Category Page')],
            ['value' => '2', 'label' => __('Product Page')]
        ];
    }

    public function getOptions()
    {
        $res = [];
        foreach ($this->getOptionArray() as $index => $value) {
            $res[] = ['value' => $index, 'label' => $value];
        }
        return $res;
    }
}