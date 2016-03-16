<?php
namespace App\Frontend\Modules\News;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\Comment;
use \FormBuilder\CommentFormBuilder;
use \OCFram\FormHandler;
use Entity\News;
use FormBuilder\NewsFormBuilder;
use PHPMailer;

class NewsController extends BackController
{
    public function executeIndex(HTTPRequest $request)
    {
        $nombreNews = $this->app->config()->get('nombre_news');

        $nombreCaracteres = $this->app->config()->get('nombre_caracteres');

        // On ajoute une définition pour le titre.
        $this->page->addVar('title', 'Liste des ' . $nombreNews . ' dernières news');

        // On récupère le manager des news.
        $manager = $this->managers->getManagerOf('News');

        $listeNews = $manager->getList(0, $nombreNews);


        foreach ($listeNews as $news) {
            if (strlen($news->contenu()) > $nombreCaracteres) {
                $debut = substr($news->contenu(), 0, $nombreCaracteres);

                if ((strrpos($debut, ' ') == false)) {
                    $debut = substr($debut, 0, $nombreCaracteres - 3) . '(...)';

                } else {
                    $debut = substr($debut, 0, strrpos($debut, ' ')) . '...';
                }

                $news->setContenu($debut);
            }


            // On ajoute la variable $listeNews à la vue.
            $Newsshow[$news->id()] = $this->page->getSpecificLink('News', 'show', array($news->id()));


        }
        $this->page->addVar('listeNews', $listeNews);

        $this->page->addVar('Newsshow', $Newsshow);

        $this->Build();


    }

    public function executeShow(HTTPRequest $request)
    {
        $news = $this->managers->getManagerOf('News')->getUnique($request->getData('id'));
        $auteur = $this->managers->getManagerOf('News')->getIdOfAuthorUsingId($request->getData('id'));

        if (empty($news)) {
            $this->app->httpResponse()->redirect404();
        }
        $this->page->addVar('title', $news->titre());
        $this->page->addVar('news', $news);
        $comments = $this->managers->getManagerOf('Comments')->getListOf($news->id());
        $authors = $this->managers->getManagerOf('Users')->getAuthorUsingNewsComments($news->id());


        if ($auteur != NULL) {
            $Newsshowauthoruser [$news->auteur()] = $this->page->getSpecificLink('News', 'showauthoruser', array($auteur->id()));
            $auteur->clean_msg();

        } else {
            $Newsshowauthoruser [$news->auteur()] = NULL;
        }

        $this->page->addVar('Newsshowauthoruser', $Newsshowauthoruser);


        $NewsinsertComment[$news->id()] = $this->page->getSpecificLink('News', 'insertComment', array($news->id()));
        $this->page->addVar('NewsinsertComment', $NewsinsertComment);


        if ($comments != NULL) {
            foreach ($comments as $com) {

                $NewsupdateComment[$com->id()] = $this->page->getSpecificLink('News', 'updateComment', array($com->id()));
                $NewsdeleteComment[$com->id()] = $this->page->getSpecificLink('News', 'deleteComment', array($com->id()));

                if ($authors != NULL) {
                    foreach ($authors as $auth) {
                        if ($auth->login() == $com['auteur']) {

                            $Newsshowuser [$com['auteur']] = $this->page->getSpecificLink('News', 'showuser', array($auth->id()));
                        } else $Newsshowuser [$com['auteur']] = NULL;

                    }

                }
            }
            $this->page->addVar('Newsshowuser', $Newsshowuser);
            $this->page->addVar('NewsupdateComment', $NewsupdateComment);
            $this->page->addVar('NewsdeleteComment', $NewsdeleteComment);


        }
        $this->page->addVar('authors', $authors);
        $this->page->addVar('auteur', $auteur);
        $this->page->addVar('comments', $comments);

        $this->Build();

    }

    public function executeInsertComment(HTTPRequest $request)
    {
        //$this->setHtml(false);
        // Si le formulaire a été envoyé.
        $this->Build();

        if ($request->method() == 'POST') {
            if ($this->app->user()->isUser() == true || $this->app->user()->isAuthenticated() == true) {
                $comment = new Comment([
                    'news' => $request->getData('news'),
                    'auteur' => $this->app->user()->getAttribute('log'),
                    'contenu' => $request->postData('contenu'),
                    'email' => $this->app->user()->getAttribute('mail'),

                ]);
                $email= $this->app->user()->getAttribute('mail');

            } else {
                $comment = new Comment([
                    'news' => $request->getData('news'),
                    'auteur' => $request->postData('auteur'),
                    'contenu' => $request->postData('contenu'),
                    'email' => $request->postData('email'),

                ]);
                $email=$request->postData('email');
            }
        } else {
            $comment = new Comment;
        }

        $formBuilder = new CommentFormBuilder($comment);
        if ($this->app->user()->isUser() == true || $this->app->user()->isAuthenticated() == true) {
            $formBuilder->buildUser();
        } else {
            $formBuilder->build();
        }
        $form = $formBuilder->form();

        $formHandler = new FormHandler($form, $this->managers->getManagerOf('Comments'), $request);

        if ($formHandler->process()) {
            $this->sendmail($request->getData('news'),$email);

            $this->app->user()->setFlash('Le commentaire a bien été ajouté, merci !');

            $this->app->httpResponse()->redirect('news-' . $request->getData('news') . '.html');
        }

        $this->page->addVar('comment', $comment);
        $this->page->addVar('form', $form->createView());
        $this->page->addVar('title', 'Ajout d\'un commentaire');


    }

    /*Ajout user */
    public function executeInsert(HTTPRequest $request)
    {
        $this->RedirectNews404($request);

        $this->processForm($request);

        $this->page->addVar('title', 'Ajout d\'une news');

        $this->Build();



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
                    $Newsupdate[$news->id()] = $this->page->getSpecificLink('News', 'update', array($news->id()));
                    $Newsdelete[$news->id()] = $this->page->getSpecificLink('News', 'delete', array($news->id()));

                }
                $this->page->addVar('Newsupdate', $Newsupdate);
                $this->page->addVar('Newsdelete', $Newsdelete);
            }
            if ($listeCom != NULL) {
                foreach ($listeCom as $com) {
                    $NewsupdateComment[$com->id()] = $this->page->getSpecificLink('News', 'updateComment', array($com->id()));
                    $NewsdeleteComment[$com->id()] = $this->page->getSpecificLink('News', 'deleteComment', array($com->id()));

                }
                $this->page->addVar('NewsupdateComment', $NewsupdateComment);
                $this->page->addVar('NewsdeleteComment', $NewsdeleteComment);
            }
            if ($listeComnews != NULL) {
                foreach ($listeComnews as &$comnews) {
                    /*$comnews['titre']=htmlentities('<br>');
                       $comnews[1]=htmlentities('<br>');
                    var_dump($comnews);*/

                    $Newsshow[$comnews['nid']] = $this->page->getSpecificLink('News', 'show', array([$comnews['nid']]));

                }

                $this->page->addVar('Newsshow', $Newsshow);
            }

            $this->page->addVar('listeComnews', $listeComnews);
            $this->page->addVar('listeNews', $listeNews);
            $this->page->addVar('listeCom', $listeCom);
            $this->page->addVar('log', $this->app()->user()->getAttribute('log'));

        $this->Build();


    }



    public function executeDeleteComment(HTTPRequest $request)
    {
                $this->RedirectComments404($request);


                $this->managers->getManagerOf('Comments')->delete($request->getData('id'));

                $this->app->user()->setFlash('Le commentaire a bien été supprimé !');

                $this->app->httpResponse()->redirect('.');


        $this->Build();

    }

    public function executeUpdateComment(HTTPRequest $request)
    {
        $this->RedirectComments404($request);

            $this->page->addVar('title', 'Modification d\'un commentaire');

                if ($request->method() == 'POST') {
                    $comment = new Comment([
                        'id' => $request->getData('id'),
                        'auteur' => $this->app->user()->getAttribute('log'),
                        'contenu' => $request->postData('contenu')
                    ]);
                } else {
                    $comment = $this->managers->getManagerOf('Comments')->get($request->getData('id'));
                }

                $formBuilder = new CommentFormBuilder($comment);
                $formBuilder->buildUser();

                $form = $formBuilder->form();

                $formHandler = new FormHandler($form, $this->managers->getManagerOf('Comments'), $request);

                if ($formHandler->process()) {
                    $this->app->user()->setFlash('Le commentaire a bien été modifié');

                    $this->app->httpResponse()->redirect('.');
                }

                $this->page->addVar('form', $form->createView());


        $this->Build();
        }


    public function executeUpdate(HTTPRequest $request)
    {
        $this->RedirectNews404($request);

        $this->processForm($request);
     $this->page->addVar('title', 'Modification d\'une news');


        $this->Build();
    }

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

    public function processForm(HTTPRequest $request)
    {
        if ($request->method() == 'POST') {
            $news = new News([
                'auteur' => $this->app->user()->getAttribute('log'),
                'titre' => $request->postData('titre'),
                'contenu' => $request->postData('contenu')
            ]);

            if ($request->getExists('id')) {
                $news->setId($request->getData('id'));
            }
        } else {
            // L'identifiant de la news est transmis si on veut la modifier
            if ($request->getExists('id')) {

                $news = $this->managers->getManagerOf('News')->getUnique($request->getData('id'));


            } else {
                $news = new News;
            }
        }

        $formBuilder = new NewsFormBuilder($news);
        $formBuilder->Userbuild();

        $form = $formBuilder->form();

        $formHandler = new FormHandler($form, $this->managers->getManagerOf('News'), $request);

        if ($formHandler->process()) {
            $this->managers->getManagerOf('News')->addnewsUser($this->app->user()->getAttribute('id'));
            $this->app->user()->setFlash($news->isNew() ? 'La news a bien été ajoutée !' : 'La news a bien été modifiée !');

            $this->app->httpResponse()->redirect('/./');
        }
        /** @var  \OCFram\Form */

        $this->page->addVar('form', $form->createView());
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
        $ListCom = $this->managers->getManagerOf('Comments')->getListByAuthor($auteur->login());


        $listenews = $this->managers->getManagerOf('News')->getListByAuthor($auteur->login());

        $this->page->addVar('listnews', $listenews);
        $this->page->addVar('listcom', $ListCom);
        $this->page->addVar('auteur', $auteur);


        $this->Build();
    }

    public function sendmail($id,$email)
    {
        $listcomment = $this->managers->getManagerOf('Comments')->getListOfDistinct($id);

        if ($listcomment != NULL) {
            foreach ($listcomment as $com) {

                if ($com->email() != NULL && $com->email() != $email) {


                    $mail = new PHPMailer();
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->SMTPDebug= 0;
                    $mail->Port = 465;
                    $mail->SMTPSecure ="ssl";
                    $mail->isHTML(true) ;
                    $mail->Username = "dreamcenturyfaformation@gmail.com";
                    $mail->Password = "UJ691vWtcdrm";

                    $mail->SetFrom('ndreamcenturyfaformation@gmail.com', 'Test');

                    // Destinataire
                    $mail->AddAddress($com->email(), $com->auteur());
                    // Objet
                    $mail->Subject = ' Une nouvelle personne a aussi commenté la news! Réagissez vite ! ';
                    //Message
                    $mail->MsgHTML('Une nouvelle personne a aussi commenté la news');

                    if (!$mail->Send()) {
                        echo 'Erreur : ' . $mail->ErrorInfo;
                    } else {
                        echo 'Message envoyé !';
                    }
                }

            }
        }

        $this->Build();
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
        elseif( !($this->app()->user()->isUser()) && ($request->getExists('id') != true ) && $this->app()->name()=='Frontend')
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

    public function executeTest(HTTPRequest $request)
    {
       $this->page()->addVar('toto', 'Maison');
    }
}