<?php
namespace App\Backend\Modules\News;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\News;
use \Entity\Comment;
use \FormBuilder\CommentFormBuilder;
use \FormBuilder\NewsFormBuilder;
use \OCFram\FormHandler;

class NewsController extends BackController
{
    public function executeDelete(HTTPRequest $request)
    {
       $this->RedirectNews404($request);

        $newsId = $request->getData('id');

        $this->managers->getManagerOf('News')->delete($newsId);
        $this->managers->getManagerOf('Comments')->deleteFromNews($newsId);

        $this->app->user()->setFlash('La news a bien été supprimée !');

        $this->app->httpResponse()->redirect('.');


        $this->Build();
    }

    public function executeDeleteComment(HTTPRequest $request)
    {
      $this->RedirectComments404($request);

        $this->managers->getManagerOf('Comments')->delete($request->getData('id'));

        $this->app->user()->setFlash('Le commentaire a bien été supprimé !');



        $this->Build();
    }

    public function executeIndex(HTTPRequest $request)
    {
        $this->Build();

        $this->page->addVar('title', 'Gestion des news');

        $manager = $this->managers->getManagerOf('News');

        $this->page->addVar('listeNews', $manager->getList());

        $this->page->addVar('nombreNews', $manager->count());


    }

    public function executeInsert(HTTPRequest $request)
    {
        $this->processForm($request);

        $this->page->addVar('title', 'Ajout d\'une news');

        $this->Build();
    }

    public function executeUpdate(HTTPRequest $request)
    {
       $this->RedirectNews404($request);

        $this->processForm($request);

        $this->page->addVar('title', 'Modification d\'une news');


        $this->Build();
    }

    public function executeUpdateComment(HTTPRequest $request)
{
   $this->RedirectComments404($request);

    $this->page->addVar('title', 'Modification d\'un commentaire');

    if ($request->method() == 'POST')
    {
        $comment = new Comment([
            'id' => $request->getData('id'),
            'auteur' => $request->postData('auteur'),
            'contenu' => $request->postData('contenu')
        ]);
    }
    else
    {
        $comment = $this->managers->getManagerOf('Comments')->get($request->getData('id'));
    }

    $formBuilder = new CommentFormBuilder($comment);
    $formBuilder->build();

    $form = $formBuilder->form();

    $formHandler = new FormHandler($form, $this->managers->getManagerOf('Comments'), $request);

    if ($formHandler->process())
    {
        $this->app->user()->setFlash('Le commentaire a bien été modifié');

        $this->app->httpResponse()->redirect('/admin/');
    }

    $this->page->addVar('form', $form->createView());

    $this->Build();
}

    public function processForm(HTTPRequest $request)
    {
        if ($request->method() == 'POST')
        {
            $news = new News([
                'auteur' => $this->app->user()->getAttribute('log'),                'titre' => $request->postData('titre'),
                'contenu' => $request->postData('contenu')
            ]);

            if ($request->getExists('id'))
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
        {     $this->managers->getManagerOf('News')->addnewsUser($this->app->user()->getAttribute('id'));
            $this->app->user()->setFlash($news->isNew() ? 'La news a bien été ajoutée !' : 'La news a bien été modifiée !');

            $this->app->httpResponse()->redirect('/admin/');
        }

        $this->page->addVar('form', $form->createView());
    }
    public function RedirectNews404 ( HTTPRequest $request)
    {
        if (($request->getExists('id') == TRUE) && ($this->managers->getManagerOf('News')->getUnique($request->getData('id')) == NULL)) {
            if ($this->app()->name() == 'Frontend') {
                $this->app()->user()->setFlash('Accès interdit');
                $this->app()->httpResponse()->redirect('/');
            }
            else
            {
                $this->app()->user()->setFlash('Accès interdit');
                $this->app()->httpResponse()->redirect('/admin/');

            }
        }
        elseif (($this->managers->getManagerOf('News')->getUnique($request->getData('id')) != NULL ) && ($request->getExists('id') == TRUE ))
        {
            if ($this->app()->name() == 'Frontend') {
                if (!($this->app()->user()->isUser()) || ($this->app()->user()->getAttribute('log') != $this->managers->getManagerOf('News')->getUnique($request->getData('id'))->auteur())) {
                    if (!($this->app()->user()->isUser()))
                    {
                        $this->app()->user()->setFlash('Veuillez vous connecter');
                    }
                    else {
                        $this->app()->user()->setFlash('Accès interdit');

                    }
                    $this->app()->httpResponse()->redirect('/');
                }

            } elseif  ($this->app()->name() == 'Backend') {
                if (!($this->app()->user()->isAuthenticated()))
                {
                    $this->app()->httpResponse()->redirect('/admin/');
                }

            }

        }
        elseif( !($this->app()->user()->isUser()) && ($request->getExists('id') != true ) && ($this->app()->name()=='Frontend') && !($this->app()->user()->isAuthenticated()))
        {
            $this->app()->user()->setFlash('Veuillez vous connecter');

            $this->app()->httpResponse()->redirect('/');

        }

    }
    public function RedirectComments404 (HTTPRequest $request)
    {
        if (($request->getExists('id') == TRUE) && ($this->managers->getManagerOf('Comments')->get($request->getData('id')) == false)) {

            if ($this->app()->name() == 'Frontend') {
                $this->app()->user()->setFlash('Accès interdit');
                $this->app()->httpResponse()->redirect('/');
            }
            else
            {
                $this->app()->user()->setFlash('Accès interdit');
                $this->app()->httpResponse()->redirect('/admin/');

            }



        }
        elseif (($this->managers->getManagerOf('Comments')->get($request->getData('id')) != false) && ($request->getExists('id') == TRUE ))
        {
            if ($this->app()->name() == 'Frontend') {

                if (!($this->app()->user()->isUser()) || ($this->app()->user()->getAttribute('log') != $this->managers->getManagerOf('Comments')->get($request->getData('id'))->auteur())) {

                    if (!($this->app()->user()->isUser()))
                    {
                        $this->app()->user()->setFlash('Veuillez vous connecter');
                    }
                    else {

                        $this->app()->user()->setFlash('Accès interdit');

                    }
                    $this->app()->httpResponse()->redirect('/');
                }

            } elseif  ($this->app()->name() == 'Backend') {

                if (!($this->app()->user()->isAuthenticated()))
                {
                    $this->app()->httpResponse()->redirect('/admin/');
                }

            }

        }

    }
    public function executeMynews(HTTPRequest $request)
    {
        $this->RedirectNews404($request);

        $this->page->addVar('title', 'Mes news');
        $manager = $this->managers->getManagerOf('News');

        $listeNews = $manager->getListByAuthor($this->app()->user()->getAttribute('log'));
        $listeCom = $this->managers->getManagerOf('Comments')->getListByAuthor($this->app()->user()->getAttribute('log'));
        $listeComnews = $this->managers->getManagerOf('Comments')->getListByCommentAuthor($this->app()->user()->getAttribute('log'));


        if ($listeNews != NULL) {
            foreach ($listeNews as $news) {
               $news->setLink('update', $this->page->getSpecificLink('News', 'update', array($news->id())));
                $news->setLink('delete',$this->page->getSpecificLink('News', 'delete', array($news->id())));
               $news->setLink('show',$this->page->getSpecificLink('News', 'show', array($news->id())));

            }

        }
        if ($listeComnews != NULL) {
            foreach ($listeComnews as &$comnews) {

                $new= new News([
                    'id' => $comnews['nid'],
                    'titre' => $comnews['titre']

                ]);

                foreach($listeCom as $com)
                {
                    if ($com->news() == $new->id())
                    {
                        $com->setNews($new);
                    }
                }


            }

            //
        }

        $this->page->addVar('listeNews', $listeNews);
        $this->page->addVar('listeCom', $listeCom);

        $this->page->addVar('log', $this->app()->user()->getAttribute('log'));
        ;

        $this->Build();


    }

    public function executeShowuser(HTTPRequest $request)
    {
        if ($request->method() == 'POST') {
            $auteur = $request->postData('auteur');

        } else {
            $auteur = $this->managers->getManagerOf('Users')->get($request->getData('id'));


        }
        if ($auteur != NULL) {
            $ListCom = $this->managers->getManagerOf('Comments')->getListByAuthor($auteur->login());
            $listenews = $this->managers->getManagerOf('News')->getListByAuthor($auteur->login());

            $this->page->addVar('listnews', $listenews);
            $this->page->addVar('listcom', $ListCom);

            $this->page->addVar('auteur', $auteur);


        }


        $this->Build();
    }

    public function executeShowauthoruser(HTTPRequest $request)
    {
        if ($request->method() == 'POST') {
            $auteur = $request->postData('auteur');

        } else {

            $auteur = $this->managers->getManagerOf('Users')->get($request->getData('id'));

        }
        $ListCom = $this->managers->getManagerOf('Comments')->getListByAuthor($auteur->id());


        $listenews = $this->managers->getManagerOf('News')->getListByAuthor($auteur->id());

        $this->page->addVar('listnews', $listenews);
        $this->page->addVar('listcom', $ListCom);
        $this->page->addVar('auteur', $auteur);


        $this->Build();
    }
}
