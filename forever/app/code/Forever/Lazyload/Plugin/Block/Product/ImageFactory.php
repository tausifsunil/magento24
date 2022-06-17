<?php
namespace Forever\Lazyload\Plugin\Block\Product;

class ImageFactory
{
    const IMAGE_BORDER_TEMPLATE = 'Forever_Lazyload::product/image_with_borders.phtml';

    /**
     * @var \Forever\Lazyload\Helper\Filter
     */
    protected $filterHelper;

    /**
     * @param \Forever\Lazyload\Helper\Filter $filterHelper
     */
    public function __construct(
        \Forever\Lazyload\Helper\Filter $filterHelper
    ) {
        $this->filterHelper = $filterHelper;
    }

    public function afterCreate(
        \Magento\Catalog\Block\Product\ImageFactory $subject,
        $result
    ) {
        if ($this->filterHelper->isEnable()) {
            $result->setTemplate(self::IMAGE_BORDER_TEMPLATE);
        }
        return $result;
    }
}
