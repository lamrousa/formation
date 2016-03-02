<?php
/**
 * Created by PhpStorm.
 * User: alamrous
 * Date: 02/03/2016
 * Time: 11:38
 */
namespace OCFram;

class HTTPResponse extends ApplicationComponent
{
protected $page ;

    public function addHeader ($header)
    {
    header ($header);
    }
    public function redirect ($location)
    {
    header($location);
    }
    public function send()
    {
    exit($this->page->getGeneratedPage() ) ; /* A voir */
    }
    public function redirect404()
    {

            $this->page = new Page($this->app);
            $this->page->setContentFile(__DIR__.'/../../Errors/404.html');

            $this->addHeader('HTTP/1.0 404 Not Found');

            $this->send();

    }
    public function setCookie ($name,$value= '',$expire =0, $path=NULL,$domain= NULL ,  $secure = NULL, $httpOnly=true )
    {
        setcookie($name,$value,$expire,$path,$domain,$secure,$httpOnly);
    }
}