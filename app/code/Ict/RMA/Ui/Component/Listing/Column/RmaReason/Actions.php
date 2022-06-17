<?php

/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_RMA
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */

namespace Ict\RMA\Ui\Component\Listing\Column\RmaReason;

class Actions extends \Magento\Ui\Component\Listing\Columns\Column
{
    
    /**
     * Edit RMA Reason Path
     */
    public const URL_EDIT_PATH = 'rma/index/editreason';
    
    /**
     * RMA Reason ID
     */
    public const RMA_REASON_ID = 'rma_reason_id';

    /**
     * RMA Field Name
     */
    public const NAME_FIELD = 'name';

    /*
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
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
                if (isset($item[self::RMA_REASON_ID])) {
                    $item[$this->getData(self::NAME_FIELD)] = [
                        'edit' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_EDIT_PATH,
                                [
                                    'id' => $item[self::RMA_REASON_ID],
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
