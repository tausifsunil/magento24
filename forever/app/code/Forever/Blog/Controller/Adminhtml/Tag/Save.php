<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Forever\Blog\Controller\Adminhtml\Tag;

/**
 * Blog post save controller
 */
class Save extends \Magento\Backend\App\Action
{
    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Forever\Blog\Model\TagFactory     $tagFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Forever\Blog\Model\TagFactory $tagFactory
    ) {
       
        $this->backSession = $context->getSession();
        $this->tagFactory = $tagFactory;
        
        parent::__construct($context);
    }
    public function execute()
    {
        $data = $this->getRequest()->getPost();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $tagData = $this->tagFactory->create();
            $id = $this->getRequest()->getParam('tag_id');
            if ($id) {
                $tagData->load($id);
            }
            $tagData->setStatus($data['status']);
            $tagData->setTitle($data['title']);
            try {
                $tagData->save();
                $this->messageManager->addSuccess(__('Row data has been successfully saved.'));
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/tag/edit', ['tag_id' => $tagData->getTagId(),
                    '_current' => true]);
                }
                return $resultRedirect->setPath('*/tag/index');
            } catch (\Magento\Framework\Model\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Data.'));
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError(__($e->getMessage()));
            }
            return $resultRedirect->setPath('*/tag/edit', ['tag_id' => $this->getRequest()->getParam('tag_id')]);
        }
        return $resultRedirect->setPath('*/tag/index');
    }
}
