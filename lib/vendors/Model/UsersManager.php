<?php
/**
 * Created by PhpStorm.
 * User: alamrous
 * Date: 08/03/2016
 * Time: 11:05
 */

namespace Model;


use OCFram\Manager;
use Entity\OutsideUser;

abstract class UsersManager extends Manager
{
    abstract public function addUser(OutsideUser $outsideUser);
    abstract public function getUser($id);
    abstract public function deleteUser($id);

}