<?php
/**
 * Created by PhpStorm.
 * User: alamrous
 * Date: 02/03/2016
 * Time: 12:40
 */

namespace OCFram;


class Route
{
    protected $action ;
    protected $module ;
    protected $url ;
    protected $varNames;
    protected $vars;
    public function __construct($url, $module,$action, array $varNames)
    {
        $this->setUrl($url);
        $this->setModule($module);
        $this->setAction($action);
        $this->setVarNames($varNames);

    }
    public function hasVars ()
    {
        return isset($this->vars) ;
    }
    public function match ($url)

        {
                                            if (preg_match('`^'.$this->url.'$`', $url, $matches))
                                            {
                                                return $matches;
                                            }
                                            else
                                            {
                                                return false;
                                            }
            }

    public function setAction($action)
    {
        $this->action = $action;
    }

    public function setModule($module)
    {
        $this->module = $module;
    }

    public function setVarNames( array $varNames)
    {
        $this->varNames = $varNames;
    }
    public  function setVars(array $vars)
    {
        $this->vars = $vars;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function action()
    {
        return $this->action;
    }

    public function module()
    {
        return $this->module;
    }

    public function url()
    {
        return $this->url;
    }

    public function varNames()
    {
        return $this->varNames;
    }

    public function vars()
    {
        return $this->vars;
    }
}