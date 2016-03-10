<?php
namespace App\Frontend\Modules\News;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\Comment;
use \FormBuilder\CommentFormBuilder;
use \OCFram\FormHandler;
use Entity\News;
use FormBuilder\NewsFormBuilder;

class NewsController extends BackController
{
    public function executeIndex(HTTPRequest $request)
    {
        $nombreNews = $this->app->config()->get('nombre_news');

        $nombreCaracteres = $this->app->config()->get('nombre_caracteres');

        // On ajoute une définition pour le titre.
        $this->page->addVar('title', 'Liste des '.$nombreNews.' dernières news');

        // On récupère le manager des news.
        $manager = $this->managers->getManagerOf('News');

        $listeNews = $manager->getList(0, $nombreNews);


        foreach ($listeNews as $news)
        {
            if (strlen($news->contenu()) > $nombreCaracteres)
            {
                $debut = substr($news->contenu(), 0, $nombreCaracteres);

                if ((strrpos($debut, ' ')==false))
                {
                    $debut = substr($debut, 0, $nombreCaracteres - 3) . '(...)';

                }

               else { $debut = substr($debut, 0, strrpos($debut, ' ')) . '...';}

                $news->setContenu($debut);
            }
        }

        // On ajoute la variable $listeNews à la vue.
        $this->page->addVar('listeNews', $listeNews);
    }

    public function executeShow(HTTPRequest $request)
    {
        $news = $this->managers->getManagerOf('News')->getUnique($request->getData('id'));

        if (empty($news))
        {
            $this->app->httpResponse()->redirect404();
        }

        $this->page->addVar('title', $news->titre());
        $this->page->addVar('news', $news);
        $this->page->addVar('comments', $this->managers->getManagerOf('Comments')->getListOf($news->id()));
    }

    public function executeInsertComment(HTTPRequest $request)
    {
        // Si le formulaire a été envoyé.

        if ($request->method() == 'POST')
        {
            if ($this->app->user()->isUser() == true || $this->app->user()->isAuthenticated() == true )
            {
                $comment = new Comment([
                    'news' => $request->getData('news'),
                    'auteur' => $this->app->user()->getAttribute('log'),
                    'contenu' => $request->postData('contenu'),
                    'email' => $this->app->user()->getAttribute('mail'),

                ]);

            }
            else
            {
            $comment = new Comment([
                'news' => $request->getData('news'),
                'auteur' => $request->postData('auteur'),
                'contenu' => $request->postData('contenu'),
                'email' => $request->postData('email'),

            ]);
        }}
        else
        {
            $comment = new Comment;
        }

        $formBuilder = new CommentFormBuilder($comment);
        if ($this->app->user()->isUser() == true || $this->app->user()->isAuthenticated() == true )
        {
            $formBuilder->buildUser();
        }
        else {
            $formBuilder->build();
            }
        $form = $formBuilder->form();

        $formHandler = new FormHandler($form, $this->managers->getManagerOf('Comments'), $request);

        if ($formHandler->process())
        {
            $this->app->user()->setFlash('Le commentaire a bien été ajouté, merci !');

            $this->app->httpResponse()->redirect('news-'.$request->getData('news').'.html');
        }

        $this->page->addVar('comment', $comment);
        $this->page->addVar('form', $form->createView());
        $this->page->addVar('title', 'Ajout d\'un commentaire');
    }
/*Ajout user */
    public function executeInsert(HTTPRequest $request)
    {
        $this->processForm($request);

        $this->page->addVar('title', 'Ajout d\'une news');
    }

    public function executeMynews(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Mes news');
        $manager = $this->managers->getManagerOf('News');


        $this->page->addVar('listeNews', $manager->getListByAuthor($this->app()->user()->getAttribute('log')));



        $this->page->addVar('nombreNews', $manager->count());
    }
   public function executeUpdate(HTTPRequest $request)
    {
        if ($this->app->user()->isUser() == true)
        {
        $this->processForm($request);

        $this->page->addVar('title', 'Modification d\'une news');
    }}

    public function executeDelete(HTTPRequest $request)
    {if ($this->app->user()->isUser() == true)
    {
        $newsId = $request->getData('id');

        $this->managers->getManagerOf('News')->delete($newsId);
        $this->managers->getManagerOf('Comments')->deleteFromNews($newsId);

        $this->app->user()->setFlash('La news a bien été supprimée !');

        $this->app->httpResponse()->redirect('.');
    }}
    public function processForm(HTTPRequest $request)
    {
        if ($request->method() == 'POST')
        {
            $news = new News([
                'auteur' => $this->app->user()->getAttribute('log'),
                'titre' => $request->postData('titre'),
                'contenu' => $request->postData('contenu')
            ]);

            if ($request->getExists('id') )
            {
                $news->setId($request->getData('id'));
            }
        }
        else
        {
            // L'identifiant de la news est transmis si on veut la modifier
            if ($request->getExists('id'))
            {

                    $news = $this->managers->getManagerOf('News')->getUnique($request->getData('id'));


            }
            else
            {
                $news = new News;
            }
        }

        $formBuilder = new NewsFormBuilder($news);
        $formBuilder->Userbuild();

        $form = $formBuilder->form();

        $formHandler = new FormHandler($form, $this->managers->getManagerOf('News'), $request);

        if ($formHandler->process())
        {
            $this->app->user()->setFlash($news->isNew() ? 'La news a bien été ajoutée !' : 'La news a bien été modifiée !');

            $this->app->httpResponse()->redirect('/./');
        }

        $this->page->addVar('form', $form->createView());
    }
    public function executeShowuser(HTTPRequest $request)
    {
        if ($request->method() == 'POST')
    {
        $auteur =  $request->postData('auteur');

    }
    else
    {
        $auteur = $this->managers->getManagerOf('Comments')->get($request->getData('id'))->auteur();
    }
        $ListCom=$this->managers->getManagerOf('Comments')->getListByAuthor($auteur);
        $listenews = $this->managers->getManagerOf('News')->getListByAuthor($auteur);

        $this->page->addVar('listnews', $listenews);
        $this->page->addVar('listcom', $ListCom);

        $this->page->addVar('auteur',$auteur);


    }
    public function executeShowauthoruser(HTTPRequest $request)
    {
        if ($request->method() == 'POST')
        {
            $auteur =  $request->postData('auteur');

        }
        else
        {
            $auteur = $this->managers->getManagerOf('News')->getUnique($request->getData('id'))->auteur();
        }
        $ListCom=$this->managers->getManagerOf('Comments')->getListByAuthor($auteur);


        $listenews = $this->managers->getManagerOf('News')->getListByAuthor($auteur);

        $this->page->addVar('listnews', $listenews);
        $this->page->addVar('listcom', $ListCom);
        $this->page->addVar('auteur',$auteur);




    }
}