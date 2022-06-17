<?php

namespace Forever\LayeredNavigation\Helper;

use Magento\Customer\Model\Session;
use Magento\Framework\DataObject;
use Forever\LayeredNavigation\Helper\AbstractData;

class Data extends AbstractData
{
    const CONFIG_MODULE_PATH = 'layered_navigation';
    const FILTER_TYPE_LIST = 'list';

    /**
     * @param null $storeId
     *
     * @return mixed
     */
    public function ajaxEnabled($storeId = null)
    {
        // $sai =  $this->getConfigGeneral('ajax_enable', $storeId) && $this->isModuleOutputEnabled();
        // echo"<pre>";print_r($sai);die;
        return $this->getConfigGeneral('ajax_enable', $storeId) && $this->isModuleOutputEnabled();
    }

    /**
     * @param $filters
     *
     * @return mixed
     */
    public function getLayerConfiguration($filters)
    {
        $params       = $this->_getRequest()->getParams();
        $filterParams = [];
        foreach ($params as $key => $param) {
            if ($key === 'amp;dimbaar') {
                continue;
            }
            $filterParams[htmlentities($key)] = htmlentities($param);
        }
        $config = new DataObject([
            'active' => array_keys($filterParams),
            'params' => $filterParams,
            'isCustomerLoggedIn' => $this->objectManager->create(Session::class)->isLoggedIn()
        ]);

        return self::jsonEncode($config->getData());
    }
}
