<?php
/**
 * @author Ict Team
 * @copyright Copyright (c) 2017 Ict (http://icreativetechnologies.com/)
 * @package Ict_Shopbybrand
 */

namespace Ict\Shopbybrand\Block\Adminhtml\Maker\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic as GenericForm;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class Category extends GenericForm implements TabInterface
{

    protected function _prepareForm()
    {
        /** @var \Ict\Shopbybrand\Model\Maker $maker */ 
        $maker = $this->_coreRegistry->registry('ict_shopbybrand_maker');
        $form   = $this->_formFactory->create();
        $form->setHtmlIdPrefix('maker_');
        $form->setFieldNameSuffix('maker');
        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend'=>__('Categories'),
            'class' => 'fieldset-wide')
        );
        $fieldset->addField('categories_ids', '\Ict\Shopbybrand\Block\Adminhtml\Helper\Category', array(
            'name'  => 'categories_ids',
            'label'     => __('Categories'),
            'title'     => __('Categories'),

        ));

        if (is_null($maker->getCategoriesIds())) {
            $maker->setCategoriesIds($maker->getCategoryIds());
        }
        $form->addValues($maker->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     * @return string
     */
    public function getTabLabel()
    {
        return __('Categories');
    }

    /**
     * Prepare title for tab
     * @return string
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * Can show tab in tabs
     * @return boolean
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Tab is hidden
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }
}
