<?php
/**
 * Created by PhpStorm.
 * User: alamrous
 * Date: 02/03/2016
 * Time: 12:14
 */

namespace OCFram;


class ApplicationComponent
{
protected $app ;

  public function __construct($app)
  {
      $this->app = $app ;

  }

    public function app()
    {
        return $this->app;
    }
}