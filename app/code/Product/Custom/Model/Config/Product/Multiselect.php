<?php
namespace Product\Custom\Model\Config\Product;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Eav\Model\Entity\Attribute\Source\SourceInterface;
use Magento\Framework\Option\ArrayInterface;

class Multiselect extends AbstractSource implements SourceInterface, ArrayInterface
{

/**
 * Retrieve All options
 *
 * @return array
 */
    public function getAllOptions()
    {
        return [
            ['value' => 1, 'label' => __('First')],
            ['value' => 2, 'label' => __('Second')],
            ['value' => 3, 'label' => __('Third')],
            ['value' => 4, 'label' => __('Fourth')]
        ];
    }
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
