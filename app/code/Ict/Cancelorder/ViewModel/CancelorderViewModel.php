<?php

/**
 * Copyright © Icreative Technologies. All rights reserved.
 *
 * @author : Icreative Technologies
 * @package : Ict_Cancelorder
 * @copyright : Copyright © Icreative Technologies (https://www.icreativetechnologies.com/)
 */

namespace Ict\Cancelorder\ViewModel;

class CancelorderViewModel implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Sales\Helper\Reorder
     */
    protected $reorder;

    /**
     * @var \Magento\Framework\Data\Helper\PostHelper
     */
    protected $postHelper;

    /**
     * @param Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param Magento\Sales\Helper\Reorder $reorder
     * @param Magento\Framework\Data\Helper\PostHelper $postHelper
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Sales\Helper\Reorder $reorder,
        \Magento\Framework\Data\Helper\PostHelper $postHelper
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->reorder = $reorder;
        $this->postHelper = $postHelper;
    }

    /**
     * Get config value
     *
     * @param string $configPath
     * @return string
     */
    public function getConfig($configPath)
    {
        return $this->scopeConfig->getValue(
            $configPath,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get re-order detail
     *
     * @param int $id
     * @return array
     */
    public function getReorder($id)
    {
        return $this->reorder->canReorder($id);
    }

    /**
     * Get data
     *
     * @param $url
     * @return array
     */
    public function getPostData($url)
    {
        return $this->postHelper->getPostData($url);
    }
}
