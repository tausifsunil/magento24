<?php
/**
 * @author Ict Team
 * @copyright Copyright (c) 2017 Ict (http://icreativetechnologies.com/)
 * @package Ict_Shopbybrand
 */

namespace Ict\Shopbybrand\Controller\Adminhtml\Maker;

use Ict\Shopbybrand\Model\Maker;

class MassDelete extends MassAction
{

    protected $successMessage = 'A total of %1 record(s) have been deleted';

    protected $errorMessage = 'An error occurred while deleting record(s).';

    /**
     * @param $maker
     * @return $this
     */
    protected function doTheAction(Maker $maker)
    {
        $maker->delete();
        return $this;
    }
}
