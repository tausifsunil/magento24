<?php

namespace Forever\Productlabel\ViewModel;

class ProductLabelViewModel implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var Scopeconfig
     */
    protected $scopeconfig;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeconfig
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeconfig
    ) {
        $this->scopeconfig = $scopeconfig;
    }

    /**
     * Returns Product discount price with product attributte value.
     *
     * @return array
     */
    public function getProductlabel($product)
    {
        if ($product->getProductlabel()) {
            $alllabel = explode(',', $product->getProductlabel());
            $productlabAttr =  $product->getResource()->getAttribute('productlabel');
            $allproductlabel = [];
            if ($productlabAttr->usesSource()) {
                foreach ($alllabel as $value) {
                    $productatt = $productlabAttr->getSource()->getOptionText($value);
                    $allproductlabel[] = (string)$productatt;
                }
            }

            $productlabelValue = json_decode(json_encode($allproductlabel));
            $discountper=0;
            if ($product->getSpecialPrice() > 0) {
                $discountper = 100 - round(((float)$product->getSpecialPrice() / (float)$product->getPrice()) * 100);
            }
            $arr = array_search('Discount', $productlabelValue);
            $labelUpdate = $allproductlabel;
            foreach ($productlabelValue as $k => $label) {
                if ($label == 'Discount') {
                    if ($product->getSpecialPrice() && $discountper) {
                        $labelUpdate[$k] = $discountper;
                    } else {
                        unset($labelUpdate[$k]);
                    }
                }
            }
            return $labelUpdate;
        }
    }

    /**
     * Return Scope Config Value
     *
     * @return bool
     */
    public function getScopeconfig($value)
    {
        return $this->scopeconfig->getValue(
            $value,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Return Product Option
     *
     * @return array
     */
    public function getProductoption($product)
    {
        if ($select = $product->getProductoption()) {
            $selectAttr =  $product->getResource()->getAttribute('productoption');
            if ($selectAttr->usesSource()) {
                $select = $selectAttr->getSource()->getOptionText($select);
                return $productoption = strtolower(str_replace(' ', '_', $select));
            }
        }
    }
}
