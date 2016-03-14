<?php
/**
 * Created by PhpStorm.
 * User: alamrous
 * Date: 14/03/2016
 * Time: 17:38
 */

namespace OCFram;


class LayoutBuilder
{
    protected $title = 'Mon super site';
    protected $css = '<link rel="stylesheet" href="/css/Envision.css" type="text/css" />' ;
    protected $meta = '<meta http-equiv="content-type" content="text/html; charset=utf-8" />';
    protected $header ='<header>';
    protected $nav ='<nav><ul>' ;
    protected $main= '<div id="content-wrap"><section id="main">';
    protected $footer='<footer>';

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setCss($css)
    {
        $this->css = '<link rel="stylesheet" href="/css/'.$css.'" type="text/css" />';
    }

    public function setMeta($meta)
    {
        $this->meta = '<meta http-equiv="content-type" content="text/html; charset='.$meta.'" />';
    }

    public function addHeader(array $header)
    {
        $this->header.=implode("",$header) ;
    }
    public function addNav($link,$value)
    {
        $this->nav.='<li> <a href ="<?='.$link.'?>">'.$value.'</a></li>';
    }
    public function addFooter($balise,$value)
    {
        $this->footer='<'.$balise.'>'.$value.'</'.$balise.'>' ;
    }

    public function buildLayout($title=NULL,array $header=NULL ,array $navs, array $footer)
    {  $this->setTitle($title);
        $this->addHeader($header);
        foreach ($navs as $nav)
        {
            $this->addNav($nav);
        }

    }
}