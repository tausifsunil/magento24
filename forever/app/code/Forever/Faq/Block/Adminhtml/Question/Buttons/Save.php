<?php
/**
 * Question Form — Save Button
 *
 * @category   Forever
 * @package    Forever_Faq
 * @author     Andresa Martins <contact@andresa.dev>
 * @copyright  Copyright (c) 2019 Forever eCommerce Solutions (https://www.forever.com.br)
 * @license    http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Forever\Faq\Block\Adminhtml\Question\Buttons;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class Save implements ButtonProviderInterface
{
    /**
     * Get Save Button Data
     *
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save Question'),
            'class' => 'primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 90,
        ];
    }

    /**
     * Generate Save Button URL for Question Form Save Button
     *
     * @return mixed
     */
    public function getSaveUrl()
    {
        $params = [];

        if (!empty($this->getRequest()->getParam('id'))) {
            $params = ['id' => $this->getRequest()->getParam('id')];
        }

        return $this->getUrl('*/*/action/save', $params);
    }
}
