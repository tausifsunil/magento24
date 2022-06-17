<?php
/**
 * Question Index Controller
 *
 * @category   Forever
 * @package    Forever_Faq
 * @author     Andresa Martins <contact@andresa.dev>
 * @copyright  Copyright (c) 2019 Forever eCommerce Solutions (https://www.forever.com.br)
 * @license    http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Forever\Faq\Controller\Adminhtml\Question;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\HttpGetActionInterface;

/**
 * @Class Index
 * package Forever\Faq\Controller\Adminhtml\Question
 */
class Index extends Action implements HttpGetActionInterface
{
    public function execute()
    {
        /** @var Raw $result */
        $result = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        return $result;
    }
}
