<?php
namespace App\Frontend;

use \OCFram\Application;
use OCFram\Page;

class FrontendApplication extends Application
{
    public function __construct()
    {
        parent::__construct();

        $this->name = 'Frontend';
    }

    public function run()
    {

        $controller = $this->getController();

        $controller->execute();



        $this->httpResponse->setPage($controller->page());

       if ($this->bool == true)
        {
            $this->httpResponse()->getPage()->setIshtml(false);
            var_dump($this->httpResponse()->getPage()->getIshtml());
        }

        $this->httpResponse->send();
    }
}