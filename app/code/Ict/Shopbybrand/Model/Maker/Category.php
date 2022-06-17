<?php
/**
 * @author Ict Team
 * @copyright Copyright (c) 2017 Ict (http://icreativetechnologies.com/)
 * @package Ict_Shopbybrand
 */

namespace Ict\Shopbybrand\Model\Maker;

use Ict\Shopbybrand\Model\ResourceModel\Maker\CollectionFactory;
use Magento\Catalog\Model\Category as CategoryModel;

class Category
{
 
    protected $makerCollectionFactory;
 
    public function __construct(
        CollectionFactory $makerCollectionFactory
    )
    {
        $this->makerCollectionFactory = $makerCollectionFactory;
    }
    /**
     * @access public
     * @param \Magento\Catalog\Model\Category $category
     * @return mixed
     */
    public function getSelectedMakers(CategoryModel $category)
    {
        if (!$category->hasSelectedMakers()) {
            $makers = [];
            foreach ($this->getSelectedMakersCollection($category) as $maker) {
                $makers[] = $maker;
            }
            $category->setSelectedMakers($makers);
        }
        return $category->getData('selected_makers');
    }
    /**
     * @param \Magento\Catalog\Model\Category $category
     * @return \Ict\Shopbybrand\Model\ResourceModel\Maker\Collection
     */
    public function getSelectedMakersCollection(CategoryModel $category)
    {
        $collection = $this->makerCollectionFactory->create()
            ->addCategoryFilter($category);
        return $collection;
    }
}

