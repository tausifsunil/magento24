<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Forever\StoreLocator\Ui\Component\Listing\Column;

use Forever\StoreLocator\Model\Store;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Directory\Model\CountryFactory;

class CountryName extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @var storeFactory
     */
    protected $storeFactory;
    /**
     * @var searchCriteria
     */
    protected $searchCriteria;
    /**
     * @var countryFactory
     */
    protected $countryFactory;
 
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        Store $storeFactory,
        SearchCriteriaBuilder $criteria,
        CountryFactory $countryFactory,
        array $components = [],
        array $data = []
    ) {
        $this->storeFactory = $storeFactory;
        $this->_searchCriteria  = $criteria;
        $this->countryFactory = $countryFactory;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }
 
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $countryCode = $item['country'];
                $country = $this->countryFactory->create()->loadByCode($countryCode);
                $item['country'] = $country->getName();
            }
        }
        return $dataSource;
    }
}
