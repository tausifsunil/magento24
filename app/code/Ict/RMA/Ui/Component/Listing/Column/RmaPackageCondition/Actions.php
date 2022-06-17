<?php

/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_RMA
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */

namespace Ict\RMA\Ui\Component\Listing\Column\RmaPackageCondition;

class Actions extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * Edit Package Condition
     */
    public const URL_EDIT_PATH = 'rma/index/editPackageCondition';
    
    /**
     * RMA Package Condition Id
     */
    public const RMA_PACKAGE_CONDITION_ID = 'rma_package_condition_id';
    
    /**
     * Field Name
     */
    public const NAME_FIELD = 'name';

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * Define The Construct
     *
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item[self::RMA_PACKAGE_CONDITION_ID])) {
                    $item[$this->getData(self::NAME_FIELD)] = [
                        'edit' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_EDIT_PATH,
                                [
                                    'id' => $item[self::RMA_PACKAGE_CONDITION_ID],
                                ]
                            ),
                            'label' => __('Edit'),
                        ]
                    ];
                }
            }
        }
        return $dataSource;
    }
}
