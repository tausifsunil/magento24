<?php

namespace Forever\LayeredNavigation\ViewModel;

class AjaxLayerViewModel implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var Scopeconfig
     */
    protected $scopeconfig;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var DataObject
     */
    protected $dataobject;

    /**
     * @var Http
     */
    protected $request;
    protected $_moduleHelper;
  
    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeconfig
     * @param \Magento\Customer\Model\Session $session
     * @param \Magento\Framework\DataObject $dataobject
     * @param \Magento\Framework\App\Request\Http $request
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeconfig,
        \Magento\Customer\Model\SessionFactory $session,
        \Magento\Framework\DataObjectFactory $dataobject,
        \Magento\Framework\App\Request\Http $request
    ) {
        $this->scopeconfig = $scopeconfig;
        $this->session = $session;
        $this->dataobject = $dataobject;
        $this->request = $request;
    }

    /**
     * @param $value
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
     * @param $filters
     *
     * @return mixed
     */
    public function getLayerConfiguration($filters)
    {
        $params       = $this->request->getParams();
        $filterParams = [];
        foreach ($params as $key => $param) {
            if ($key === 'amp;dimbaar') {
                continue;
            }
            $filterParams[htmlentities($key)] = htmlentities($param);
        }
        $setdata = $this->dataobject->create();
        $config = $setdata->setData([
            'active' => array_keys($filterParams),
            'params' => $filterParams,
            'isCustomerLoggedIn' => $this->session->create()->isLoggedIn()
        ]);
        return json_encode($config->getData());
    }
}
