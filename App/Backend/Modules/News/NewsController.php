<?php
namespace App\Backend\Modules\News;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\News;
use \Entity\Comment;
use \FormBuilder\CommentFormBuilder;
use \FormBuilder\NewsFormBuilder;
use \OCFram\FormHandler;
use OCFram\Centrale;

class NewsController extends BackController
{
    public function executeDelete(HTTPRequest $request)
    {
        $center= new Centrale();
        $center->RedirectNews404($this->app(), $this->managers(),$request);

        $newsId = $request->getData('id');

        $this->managers->getManagerOf('News')->delete($newsId);
        $this->managers->getManagerOf('Comments')->deleteFromNews($newsId);

        $this->app->user()->setFlash('La news a bien été supprimée !');

        $this->app->httpResponse()->redirect('.');


        $this->page->addVar('menu',$center->BuildMenu($this->app(),$this->page()));
    }

    public function executeDeleteComment(HTTPRequest $request)
    {
        $center= new Centrale();
        $center->RedirectComments404($this->app(), $this->managers(),$request);

        $this->managers->getManagerOf('Comments')->delete($request->getData('id'));

        $this->app->user()->setFlash('Le commentaire a bien été supprimé !');

      $this->app->httpResponse()->redirect('.');


        $this->page->addVar('menu',$center->BuildMenu($this->app(),$this->page()));
    }

    public function executeIndex(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Gestion des news');

        $manager = $this->managers->getManagerOf('News');

        $this->page->addVar('listeNews', $manager->getList());

        $this->page->addVar('nombreNews', $manager->count());

        $center = new Centrale();

        $this->page->addVar('menu',$center->BuildMenu($this->app(),$this->page()));
    }

    public function executeInsert(HTTPRequest $request)
    {
        $this->processForm($request);

        $this->page->addVar('title', 'Ajout d\'une news');
        $center = new Centrale();

        $this->page->addVar('menu',$center->BuildMenu($this->app(),$this->page()));
    }

    public function executeUpdate(HTTPRequest $request)
    {
        $center= new Centrale();
        $center->RedirectNews404($this->app(), $this->managers(),$request);

        $this->processForm($request);

        $this->page->addVar('title', 'Modification d\'une news');


        $this->page->addVar('menu',$center->BuildMenu($this->app(),$this->page()));
    }

    public function executeUpdateComment(HTTPRequest $request)
{
    $center= new Centrale();
    $center->RedirectComments404($this->app(), $this->managers(),$request);

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

    $this->page->addVar('menu',$center->BuildMenu($this->app(),$this->page()));
}

    public function processForm(HTTPRequest $request)
    {
        if ($request->method() == 'POST')
        {
            $news = new News([
                'auteur' => $request->postData('auteur'),
                'titre' => $request->postData('titre'),
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
        $formBuilder->build();

        $form = $formBuilder->form();

        $formHandler = new FormHandler($form, $this->managers->getManagerOf('News'), $request);

        if ($formHandler->process())
        {
            $this->app->user()->setFlash($news->isNew() ? 'La news a bien été ajoutée !' : 'La news a bien été modifiée !');

            $this->app->httpResponse()->redirect('/admin/');
        }

        $this->page->addVar('form', $form->createView());
    }
}
