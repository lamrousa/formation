<?php
/**
 * Created by PhpStorm.
 * User: alamrous
 * Date: 08/03/2016
 * Time: 11:23
 */

namespace Model;

use Entity\OutsideUser;


class UsersManagerPDO extends UsersManager
{
     public function addUser(OutsideUser $outsideUser)
     {

         $q=$this->dao->prepare('INSERT INTO t_app_userc SET AUC_login = :login ,AUC_password = :pass, AUC_email = :email,  AUC_state = :state, AUC_dateAdd = NOW()');
         $q->bindValue(':login', $outsideUser->login());
         $q->bindValue(':pass', str_rot13($outsideUser->password()));
         $q->bindValue(':email', $outsideUser->email());
        $q->bindValue('state', $outsideUser->state(), \PDO::PARAM_INT);
         $q->execute();
     }
     public function getUser($login)
     {
         $q=$this->dao->prepare('SELECT AUC_id,AUC_login, AUC_password, AUC_email FROM t_app_userc WHERE AUC_login = :login');
         $q->bindValue(':login', $login);
         $q->execute();
       $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\OutsideUser');

         if ($user = $q->fetch())
         {
               return $user;
         }

         return null;
     }
    public function get($id)
    {
        $q=$this->dao->prepare('SELECT AUC_id,AUC_login, AUC_password, AUC_email FROM t_app_userc WHERE AUC_id = :id');
        $q->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\OutsideUser');

        if ($user = $q->fetch())
        {
            return $user;
        }

        return null;

    }
    public function getAuthorUsingNewsComments($id)
    {
        $q=$this->dao->prepare('SELECT AUC_id,AUC_login, AUC_password, AUC_email
 FROM t_app_userc
INNER JOIN comments ON comments.auteur=AUC_login
INNER JOIN news ON news.id= :id AND comments.news=news.id');
        $q->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\OutsideUser');
        if ($user = $q->fetchAll())
        {
            return $user;
        }

        return null;
    }


     public function deleteUser($id)
     {
         $this->dao->exec('DELETE FROM t_app_userc WHERE AUC_id = '.(int) $id);

     }


}