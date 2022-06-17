<?php
/**
 * Category Testimonials FrontEnd Index Controller
 *
 * @category  Forever
 * @package   Forever_Testimonials
 * @author    Andresa Martins <contact@andresa.dev>
 * @copyright Copyright (c) 2019 Forever eCommerce Solutions (https://www.forever.com.br)
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Forever\Testimonials\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\HttpGetActionInterface;

/**
 * Class Index
 *
 * @package Forever\Testimonials\Controller\Index
 */
class Index extends Action implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Index constructor.
     *
     * @param Context     $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        return $this->resultPageFactory->create();
    }
}
