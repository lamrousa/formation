<?php
/**
 * Created by PhpStorm.
 * User: alamrous
 * Date: 03/03/2016
 * Time: 10:59
 */

namespace OCFram;


class Config extends ApplicationComponent
{
protected $vars= [];

    public function getVars($var)
    {
        if ($this->vars == NULL)
        {
        $xml = new \DOMDocument() ;
        $xml->load('App /'.$this->app()->name().'/Config/app.xml');
        $define=$xml->getElementsByTagName('define');
        foreach($define as $element)
        {
            $this->vars[$element->getAttribute('var')]=$element->getAttribute('values');
        }

        }
        if (!isset($this->vars[$var]))
        {
            throw new \RuntimeException ('Variable non existante');
        }
            return $this->vars[$var];
    }

}