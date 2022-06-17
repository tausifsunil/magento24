<?php
namespace Forever\Lazyload\Plugin\Model\Template;

class Filter
{
    const LAZYLOAD_CMS = 'lazyload/general/lazyload_cms';

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

    public function afterFilter(
        \Magento\Cms\Model\Template\Filter $filter,
        $result
    ) {
        if (is_string($result)
            && $this->filterHelper->isEnable()
            && $this->filterHelper->getConfig(self::LAZYLOAD_CMS)) {
            $result = $this->filterHelper->filter($result);
        }
        return $result;
    }
}
