<?php
namespace App\Frontend\Modules\Device;

/**
 * Created by PhpStorm.
 * User: alamrous
 * Date: 07/03/2016
 * Time: 15:04
 */

use OCFram\BackController;
use Detection\MobileDetect;
use OCFram\HTTPRequest;

 class DeviceController extends BackController
{
    public function executeShow(HTTPRequest $request)
    {
        $this->page->addVar('title','device');

        $detect= new \Mobile_Detect();
        $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'Tablet' : 'Phone') : 'Computer');
        $_SESSION['type']=$deviceType;
        $this->page->addVar('device',$deviceType);



    }



}