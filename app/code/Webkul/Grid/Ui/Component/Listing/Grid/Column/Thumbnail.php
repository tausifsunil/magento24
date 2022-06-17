<?php
namespace Webkul\Grid\Ui\Component\Listing\Grid\Column;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class Thumbnail extends \Magento\Ui\Component\Listing\Columns\Column
{
    const NAME = 'thumbnail';

    const ALT_FIELD = 'name'; //here change
    // const ALT_FIELD = 'name';
    /**
     * @var string
     */
    private $editUrl;

    private $_objectManager = null;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->urlBuilder = $urlBuilder;
        $this->_objectManager = $objectManager;
        $this->storeManager = $storeManager;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
         //echo'<pre>';print_r($dataSource);echo'</pre>';die();
            foreach ($dataSource['data']['items'] as & $item) {
                $img = $item['image'];
                $filename = $mediaUrl.'codextblog/tmp/feature/'.$item['image'];
                $item[$fieldName . '_src'] = ($img)?$filename:'';
                $item[$fieldName . '_alt'] = ($img)?$filename :'';
                $item[$fieldName . '_link'] = ($img)?$this->urlBuilder->getUrl(
                    'adminhtml/grid/edit',
                    ['id' => $item['image']]):'';
                $item[$fieldName . '_orig_src'] = ($img)?$filename:'';
            }
        }
        return $dataSource;
    }

    /**
     * @param array $row
     *
     * @return null|string
     */
    protected function getAlt($row)
    {
        $altField = $this->getData('config/altField') ?: self::ALT_FIELD;
        // echo "<pre>";print_r($altField);die();
        return isset($row[$altField]) ? $row[$altField] : null;
    }
}