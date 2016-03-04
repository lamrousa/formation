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
use Entity\Comment;

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
    public function executeShow (HTTPRequest $request)
    {
        $manager=$this->managers->getManagerOf ('News');
        $news = $manager->getUnique($request->getData('id'));
        if (empty($news))
        {
        $this->app->httpResponse()->redirect404();
        }
        $this->page->addVar('title',$news->title()) ;
        $this->page->addVar('news', $news);
        $this->page->addVar('comments',$this->managers->getManagerOf('Comments')->getListOf($news->id()));

    }

    public function executeInsertComment(HTTPRequest $request)
    {
        $this->page->addVar('title','Ajout d\'un commentaire');
        if ($request->postExists('pseudo'))
        {
            $comment = new Comment(
                [
                    'news' => $request->getData('news'),
                    'auteur' => $request->getData('auteur'),
                    'contenu' => $request->getData('contenu')

                ]
            );
            if ($comment->isValid())
            {
                $this->managers->getManagerOf('Comment')->save($comment);
                $this->app->user()->setFlash('Commentaire bien ajouté') ;
                $this->app->httpResponse()->redirect('news-'.$request->getData('news').'.html');
            }
            else
            {
                $this->page->addVar('erreurs',$comment->erreurs());
            }
            $this->page->addVar('comment',$comment);
        }
    }

}