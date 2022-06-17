<?php
namespace Ict\Attachments\Controller\Adminhtml\Product;

/*use Exception;
use Ict\Attachments\Model\ImageUploader;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
 
class Upload extends Action implements HttpPostActionInterface
{*/
    /**
     * Image uploader
     *
     * @var ImageUploader
     */
    // protected $imageUploader;
 
    /**
     * Upload constructor.
     *
     * @param Context $context
     * @param ImageUploader $imageUploader
     */
    /*public function __construct(
        Context $context,
        ImageUploader $imageUploader
    ) {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }*/
    /**
     * Upload file controller action
     *
     * @return ResultInterface
     */
    /*public function execute()
    {
        // echo 123; die;
        try {
            $result = $this->imageUploader->saveFileToTmpDir('file');
        // echo "<pre>"; print_r($result); die;
            $result['cookie'] = [
                    'name' => $this->_getSession()->getName(),
                    'value' => $this->_getSession()->getSessionId(),
                    'lifetime' => $this->_getSession()->getCookieLifetime(),
                    'path' => $this->_getSession()->getCookiePath(),
                    'domain' => $this->_getSession()->getCookieDomain(),
                ];*/
             /*if (isset($data['file'][0]['name'])) {
                $data['file'] = $data['file'][0]['name'];
            } else {
                $data['file'] = '';
            }*/   
            /*foreach ($result['file'] as $key => $value) {
                if (isset($value['url']) && isset($value['name'])) {
                    $images .= $value['name'] . ',';
                    $extension = pathinfo($value['name']);
                    $ext .= $extension['extension'] . ',';
                }
                // echo "Key : " . $key . "value : " . $value['name'];
            }
                $str = rtrim(implode(',',array_unique(explode(',', $ext))), ',');*/
            // $imageId = $this->_request->getParam('param_name', 'image');       
            // $result = $this->imageUploader->saveFileToTmpDir($imageId);
        /*} catch (Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}*/

 
// namespace Codextblog\Imageupload\Controller\Adminhtml\Feature\Image;
// namespace Forever\Megamenu\Controller\Adminhtml\Menu;

use Exception;
use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
 
class Upload extends Action implements HttpPostActionInterface
{
    /**
     * Image uploader
     *
     * @var ImageUploader
     */
    protected $imageUploader;
 
    /**
     * Upload constructor.
     *
     * @param Magento\Backend\App\Action\Context $context
     * @param \Forever\Megamenu\Model\ImageUploader $imageUploader
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Ict\Attachments\Model\ImageUploader $imageUploader
    ) {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }
    /**
     * Upload file controller action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $imageId = $this->_request->getParam('param_name', 'image');
 
        try {
            $result = $this->imageUploader->saveFileToTmpDir($imageId);
        } catch (Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
