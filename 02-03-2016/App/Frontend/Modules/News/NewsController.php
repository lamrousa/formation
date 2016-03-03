<?php
/**
 * Created by PhpStorm.
 * User: alamrous
 * Date: 03/03/2016
 * Time: 15:11
 */

namespace App\Frontend\Modules\News;


use OCFram\BackController;
use OCFram\HTTPRequest;

class NewsController extends BackController
{
    public function executeIndex (HTTPRequest $request)
    {
        $nbnews=$this->app->config()->get('nombre_news') ;
        $nbCaracteres = $this->app->config()->get('nombre_caracteres');

        $this->page->addVar('title','Liste des'.$nbnews.'dernieres news') ;
        $manager=$this->managers->getManagerOf ('News');

        $listeNews = $manager->getList (0,$nbnews);

        foreach ($listeNews as $news)
        {
            if(strlen($news->contenu()) > $nbCaracteres)
            {
                $begin = substr($news->contenu(),0,$nbCaracteres);
                $begin= substr($begin,0,strrpos($begin,'')).'...';
                $news->setContenu($begin);
            }
        }

        $this->page->addVar ('listeNews',$listeNews);


    }

}