<?php
/**
 * @author Ict Team
 * @copyright Copyright (c) 2017 Ict (http://icreativetechnologies.com/)
 * @package Ict_Shopbybrand
 */

namespace Ict\Shopbybrand\Controller\Adminhtml\Maker;

class MassEnable extends MassDisable
{
 
    protected $successMessage = 'A total of %1 brands have been enabled';
 
    protected $errorMessage = 'An error occurred while enabling brands.';
 
    protected $isActive = true;
}
