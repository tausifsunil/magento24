<?php

namespace Forever\Productlabel\Model\Config\Product;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Eav\Model\Entity\Attribute\Source\SourceInterface;
use Magento\Framework\Option\ArrayInterface;

class Productmultisel extends AbstractSource implements SourceInterface, ArrayInterface
{

    /**
     * Retrieve All options
     *
     * @return array
     */
    public function getAllOptions()
    {
        return [
            ['value' => 1, 'label' => __('New Arrival')],
            ['value' => 2, 'label' => __('Discount')],
            ['value' => 3, 'label' => __('Best Seller')]
        ];
    }

    /**
     * Get All Multi Select Options
     *
     * @return string
     */
    public function getOptionsMulti($optionIds)
    {
        $entries = explode(',', $optionIds);
        $option = $this->getAllOptions();
        $result = [];
        foreach ($option as $key => $value) {
            if (in_array($key, $entries)) {
                $result[] = $value['label'];
            }
        }
            return implode(', ', $result);
    }
}
