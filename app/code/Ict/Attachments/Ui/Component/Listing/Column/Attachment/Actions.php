<?php

namespace Ict\Attachments\Ui\Component\Listing\Column\Attachment;

class Actions extends \Magento\Ui\Component\Listing\Columns\Column
{
    const URL_EDIT_PATH = 'attachment/index/edit';
    const ATTACHMENT_ID = 'attachment_id';
    const NAME_FIELD = 'name';

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

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item[self::ATTACHMENT_ID])) {
                    $item[$this->getData(self::NAME_FIELD)] = [
                        'edit' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_EDIT_PATH,
                                [
                                    'id' => $item[self::ATTACHMENT_ID],
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
