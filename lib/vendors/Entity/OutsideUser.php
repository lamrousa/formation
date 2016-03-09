<?php
/**
 * Created by PhpStorm.
 * User: alamrous
 * Date: 08/03/2016
 * Time: 11:07
 */

namespace Entity;

use OCFram\User;

class OutsideUser extends User
{
    protected $AUC_login;
    protected $AUC_password;
    protected $AUC_email;
    protected $AUC_state;
    protected $AUC_dateAdd;
    protected $AUC_dateEnd;

    const USERE_USERC_VALID =1 ;
    const USERE_USERC_INVALID = 0 ;




    public function setLogin($login)
    { if (is_string($login) && !empty($login))
    {        $this->AUC_login = $login;
    }
        else throw new \InvalidArgumentException('Le login doit être une chaine de caractère');
    }

    public function setPassword($password)
    {
        if (empty($password))
        {
            throw new \InvalidArgumentException('le mot de passe est vide');
        }
        $this->AUC_password = $password;
    }
    public function setEmail ($email)
    {      if (is_string($login) && !empty($login))
        {
            $this->AUC_email = $email;
        }
    else throw new \InvalidArgumentException('L \'email doit etre une chaine de caractères');
    }

    public function setInvalidState()
    {
    $this->AUC_state = self::USERE_USERC_INVALID;
    }

    public function isValid()
    {
        return !(empty($this->AUC_login) ||empty($this->AUC_password));
    }

    public function setDateadd(\DateTime $dateadd)
    {
        $this->AUC_dateAdd = $dateadd;
    }

    public function login()
    {
        return $this->AUC_login;
    }

    public function password()
    {
        return $this->AUC_password;
    }

    public function state()
    {
        return $this->AUC_state;
    }


}