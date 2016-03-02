<?php
/**
 * Created by PhpStorm.
 * User: alamrous
 * Date: 02/03/2016
 * Time: 16:40
 */

namespace OCFram;


abstract class Entity implements \ArrayAccess
{
protected $erreurs = [], $id ;

    public function __construct(array $donnees = [])
    {
        if (!empty($donnees))
        {
            $this->hydrate($donnees);
        }
    }

    public function erreurs()
    {
        return $this->erreurs;
    }

    public function id()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id=(int) $id;

    }

    public function offsetExists($offset)
    {
        return isset($this->$offset) && is_callable([$this,$offset]); //Pas Compris
    }


    public function offsetGet($offset)
    {
        if (isset($this->$offset) && is_callable([$this, $offset]))
    {
        return $this->$offset() ;
    }
    }
    public function offsetSet($offset, $value)
    {      $method='set'.ucfirst($offset);
        if (isset($this->$offset ) &&  is_callable([$this,$method]))
        {
            $this->$method ($value);
        }
    }
    public function offsetUnset($offset)
    {
        throw new \Exception('Impossible de supprimer une quelconque valeur');

    }




    public function hydrate (array $donnees)
  {
      foreach ($donnees as $key => $value)
      {
          $method ='set'.ucfirst($key) ;
              if (is_callable([$this,$method]))
              {
                  $this->$method($value) ;
              }
      }
  }


}