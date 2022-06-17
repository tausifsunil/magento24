<?php
/**
 * Question Model
 *
 * @category   Forever
 * @package    Forever_Faq
 * @author     Andresa Martins <contact@andresa.dev>
 * @copyright  Copyright (c) 2019 Forever eCommerce Solutions (https://www.forever.com.br)
 * @license    http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Forever\Faq\Model;

use Magento\Framework\Model\AbstractModel;

class Question extends AbstractModel
{
    /**
     * Question Model Constructor
     */
    public function _construct()
    {
        $this->_init(ResourceModel\Question::class);
    }
}
