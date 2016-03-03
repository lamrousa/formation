<?php

namespace Model ;

use OCFram\Manager;

abstract class NewsManager extends Manager

{
    abstract public function getList($debut=-1, $limite=-1);

}