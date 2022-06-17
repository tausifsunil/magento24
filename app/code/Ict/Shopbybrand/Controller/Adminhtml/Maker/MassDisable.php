<?php
/**
 * @author Ict Team
 * @copyright Copyright (c) 2017 Ict (http://icreativetechnologies.com/)
 * @package Ict_Shopbybrand
 */

namespace Ict\Shopbybrand\Controller\Adminhtml\Maker;

use Ict\Shopbybrand\Model\Maker;

class MassDisable extends MassAction
{

    protected $successMessage = 'A total of %1 brands have been disabled';

    protected $errorMessage = 'An error occurred while disabling brands.';

    protected $isActive = false;

    /**
     * @param Maker $maker
     * @return $this
     */
    protected function doTheAction(Maker $maker)
    {
        $maker->setIsActive($this->isActive);
        $maker->save();
        return $this;
    }
}
