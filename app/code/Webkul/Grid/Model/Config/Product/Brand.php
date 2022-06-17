<?php
namespace Webkul\Grid\Model\Config\Product;

use \Webkul\Grid\Model\ResourceModel\Grid\Collection;
use \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use \Magento\Framework\Option\ArrayInterface;

class Brand extends AbstractSource implements ArrayInterface
{
    protected $brandProduct;
    protected $options;
    /*protected $customerGroup;
    protected $options;*/

    public function __construct(Collection $brandProduct)
    {
        $this->brandProduct = $brandProduct;
    }

    public function toOptionArray()
    {
        if (!$this->_options) {
            $this->options = $this->brandProduct->toOptionArray();
        }
        return $this->_options;
    }

    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->options = $this->brandProduct->toOptionArray();
        }
        return $this->options;
    }
}
