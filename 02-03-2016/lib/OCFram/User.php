<?php
/**
 * Created by PhpStorm.
 * User: alamrous
 * Date: 03/03/2016
 * Time: 10:04
 */

namespace OCFram;

session_start() ;

class User extends ApplicationComponent //Pas d'heritage ?
{
    public function getAttribute ($attr)
    {
        if (isset($_SESSION[$attr]))
        {
            return $_SESSION[$attr] ;
        }
        else
        {
            return NULL ;
        }
    }
    public function getFlash ()
    {
        if (!isset($_SESSION['flash'] ))
        {
            throw new \ErrorException('Le message est introuvable ') ;
        }
        return $_SESSION['flash'];
    }
    public function hasFlash ()
    {
        return isset($_SESSION['flash']);
    }
    public function isAuthenticated()
    {
        return (isset ($_SESSION['auth'])) ; //$_SESSION['auth']===true
    }
    public function setAttribute ($attr, $value)
    {

            $_SESSION[$attr]=$value;

    }
    public function setAuthenticated($authenticated= TRUE)
    {
        if (!is_bool($authenticated))
        {
            throw new \InvalidArgumentException('L\'argument d\'authentification doit etre un booleen') ;
        }
        $_SESSION['auth']=$authenticated;

    }
    public function setFlash ($value)
    {
        if ((!is_string($value)) || empty($value))
        {
            throw new \InvalidArgumentException('Le message doit etre une chaine de caractere');
        }

        $_SESSION['flash']=$value;
    }


}