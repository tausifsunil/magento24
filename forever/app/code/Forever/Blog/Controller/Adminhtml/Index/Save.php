<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Forever\Blog\Controller\Adminhtml\Index;

/**
 * Blog post save controller
 */
class Save extends \Magento\Backend\App\Action
{
    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Forever\Blog\Model\BlogFactory     $blogFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Forever\Blog\Model\BlogFactory $blogFactory
    ) {
       
        $this->backSession = $context->getSession();
        $this->blogFactory = $blogFactory;
        
        parent::__construct($context);
    }
    public function execute()
    {
        $data = $this->getRequest()->getPost();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $rowData = $this->blogFactory->create();
            $id = $this->getRequest()->getParam('blog_id');
            if ($id) {
                $rowData->load($id);
            }
            $tagStr = implode(', ', $data['tags']);
            $rowData->setTags($tagStr);
            $rowData->setTitle($data['title']);
            $rowData->setContentHeading($data['content_heading']);
            $rowData->setContent($data['content']);
            if (isset($data['blog_image'][0]['name'])) {
                        $rowData->setBlogImage($data['blog_image'][0]['name']);
            } else {
                        $rowData->setBlogImage(null);
            }
            $rowData->setStatus($data['status']);
            $rowData->setAuthor($data['author']);
            $urlData = $this->blogFactory->create()->load($data['url_key'], 'url_key');
            if ($id) {
                $rowData->setUrlKey($data['url_key']);
            } else {
                if ($urlData->getId()) {
                    $this->messageManager->addError(__('Duplicate Url not Allowded.'));
                    return $resultRedirect->setPath('*/*/');
                } else {
                    $rowData->setUrlKey($data['url_key']);
                }
            }
            try {
                $rowData->save();
                $this->messageManager->addSuccess(__('Row data has been successfully saved.'));
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['blog_id' => $rowData->getBlogId(),
                    '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Model\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Data.'));
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError(__($e->getMessage()));
            }
            return $resultRedirect->setPath('*/*/edit', ['blog_id' => $this->getRequest()->getParam('blog_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
