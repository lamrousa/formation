<?php
/**
 * Created by PhpStorm.
 * User: alamrous
 * Date: 04/03/2016
 * Time: 13:03
 */

namespace App\Backend\Modules\News;


use Entity\Comment;
use OCFram\BackController;
use OCFram\HTTPRequest;
use Entity\News;

class NewsController extends BackController
{
    public function executeIndex(HTTPRequest $request)
    {
        $this->page->addVar('title','Gestion des news');
        $manager= $this->managers->getManagerOf('News');
        $this->page->addVar('listeNews', $manager->getList());
        $this->page->addvar('nombrenews', $manager->count());

    }
    public function executeInsert(HTTPRequest $request)
    {
        if ($request->postExists('auteur'))
        {
            $this->processForm($request);
        }
        $this->page->addVar('title','Ajout d \'une news');
    }
    public function executeUpdate(HTTPRequest $request)
    {
        if($request->postExists('auteur'))
        {
            $this->processForm($request);
        }
        else
        {
            $this->page->addVar('news',$this->managers->getManagerOf('News')->getUnique($request->getData('id'))) ;
        }
        $this->page->addVar('title','Modification d\'une news');
    }
    public function processForm(HTTPRequest $request)
    {
        $news= new News (
        [
            'auteur'=> $request->postData('auteur'),
            'titre'=> $request->postData('titre'),
            'contenu'=> $request->postData('contenu')]);
        if ($request->postExists('id'))
        {
            $news->setId($request->postData('id'));
        }
        if ($news->isvalid())
        {
            $this->managers->getManagerOf("News")->save();
            $this->app->user()->setFlash($news->isNew()? 'la news a bien été ajoutée !' : 'La news a bien été modifiée !');
        }
        else
        {
            $this->page->addVars('erreurs',$news->erreurs());
        }
        $this->page->addVar('news',$news);

    }
    public function executeDelete(HTTPRequest $request)
    {
        $newsId = $request->getData('id');

        $this->managers->getManagersOf('News')->delete($newsId);
        $this->managers->getManagerOf('Comments')->deleteFromNews($newsId);
        $this->app->user()->setFlash('La news a bien été supprimée !');
        $this->app->httpResponse()->redirect('.');

    }
    public function executeUpdateComment(HTTPRequest $request)
    {
        $this->page->addvar('title','Modification d\'un commentaire');
        if ($request->postExists('pseudo'))
        {
            $comment = new Comment([
                'id'=> $request->getData('id'),
                'auteur'=>$request->getData('auteur'),
                'contenu'=>$request->getData('contenu')

                ]);
            if ($comment->isValid())
            {
                $this->managers->getManagerOf('Comment')->save($comment);
                $this->app->user()->setFlash('Le commentaire a bien été modifié');
                $this->app->httpResponse()->redirect('/news-'.$request->postData('news').'.html');
            }
            else
            {
                $this->page->addvar('erreur',$comment->erreurs());
            }
            $this->page->addvar('comment',$comment);
        }
        else
        {
            $this->page->addVar('comment',$this->managers->getManagerOf('Comments')->get($request->getData('id')));
        }
    }
    public function executeDeleteComment(HTTPRequest $request)
    {
        $this->managers->getManagerOf('Comments')->delete($request->getData('id'));
        $this->app->user()->setFlash('Commentaire supprimé');
        $this->app->httpResponse()->redirect('.');
    }




}