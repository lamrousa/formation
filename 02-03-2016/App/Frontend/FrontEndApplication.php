<?php
/**
 * Created by PhpStorm.
 * User: alamrous
 * Date: 03/03/2016
 * Time: 11:56
 */

namespace App\Frontend;
namespace OCFram ;


use OCFram\Application;

class FrontEndApplication extends Application
{
    public function __construct()
    {
        parent::__construct();
        $this->name= 'Frontend';

    }
    public function run()
    {
        $controller=$this->getController();
        $controller->execute() ;
        $this->httpResponse->setPage($controller->page());
        $this->httpResponse->send();
    }
}