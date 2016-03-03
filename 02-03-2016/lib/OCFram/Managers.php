<?php
/**
 * Created by PhpStorm.
 * User: alamrous
 * Date: 02/03/2016
 * Time: 15:47
 */

namespace OCFram;


class Managers
{
protected  $api =NULL ;
protected $dao=NULL;
protected $managers = [];


    public function getManagerOf($module)
    {
        if (!is_string($module) || empty($module))
        {
            throw new \InvalidArgumentException('Le module n\'existe pas');
        }
        if (!isset( $this->managers[$module]))

            {
                $manager = '\\Model\\'.$module.'Manager'.$this->api;

                $this->managers[$module] = new $manager($this->dao);
            }

            return $this->managers[$module];
    }


    public function __construct($api, $dao)
    {
        $this->api=$api;
        $this->dao=$dao;

    }

}