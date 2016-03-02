<?php
/**
 * Created by PhpStorm.
 * User: alamrous
 * Date: 02/03/2016
 * Time: 16:35
 */

namespace OCFram;


abstract class Manager
{
protected  $dao;
    public function __construct($dao)
    {
        $this->dao=$dao;
    }
}