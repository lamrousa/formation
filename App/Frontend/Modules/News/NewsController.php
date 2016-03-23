<?php
namespace App\Frontend\Modules\News;

use \OCFram\BackController;
use OCFram\HtmlComment;
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

if ($listeNews)
{
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
           $news->setLink('show',$this->page->getSpecificLink('News', 'show', array($news->id())));


        }

    ;
}
        $this->page->addVar('listeNews', $listeNews);

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
        $comment = new Comment;

        $formBuilder = new CommentFormBuilder($comment);
        if ($this->app->user()->isUser() == true || $this->app->user()->isAuthenticated() == true) {
            $formBuilder->buildUser();
        } else {
            $formBuilder->build();
        }
        $form = $formBuilder->form();

        $this->page->addVar('form', $form->createView());


        if ($auteur != NULL) {
            $news->setLink('show',$this->page->getSpecificLink('News', 'showauthoruser', array($auteur->id())));
            $auteur->clean_msg();

        } else {
            $news->setLink('user',NULL);
        }


      $news->setLink('insertComment',$this->page->getSpecificLink('News', 'insertComment', array($news->id())));
        $authors = $this->managers->getManagerOf('Users')->getAuthorUsingNewsComments($news->id());


        if ($comments != NULL) {
            foreach ($comments as $com) {

             $com->setLink('update',$this->page->getSpecificLink('News', 'updateComment', array($com->id())));
              $com->setLink('delete',$this->page->getSpecificLink('News', 'deleteComment', array($com->id())));
                $com->setLink('user',NULL);

                if ($authors != NULL) {

                    foreach ($authors as $auth) {
                        if ($auth->login() == $com['auteur']) {

                            $com->setLink('user',$this->page->getSpecificLink('News', 'showuser', array($auth->id())));
                        }
                    }

                }
            }

        }
        $this->page->addVar('authors', $authors);
        $this->page->addVar('auteur', $auteur);
        $this->page->addVar('comments', $comments);
        if($this->app()->user()->getAttribute('id') != NULL) {
         $usr=$this->app()->user()->getAttribute('id');
        }
        else
        {
            $usr='';
        }
            $this->page()->addVar('id',$usr);
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
                    'user' =>$this->app->user()->getAttribute('id')

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
            if ($this->managers->getManagerOf('Users')->getUser($request->postData('auteur')))
            {
                $this->app->user()->setFlash('Vous ne pouvez pas utiliser ce nom d\'auteur!');

                $this->app->httpResponse()->redirect('news-' . $request->getData('news') . '.html');
            }
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

    /**
     * @param HTTPRequest $request
     */
    public function executeMynews(HTTPRequest $request)
    {
        $this->RedirectNews404($request);

            $this->page->addVar('title', 'Mes news');
            $manager = $this->managers->getManagerOf('News');

            $listeNews = $manager->getListByAuthor($this->app()->user()->getAttribute('id'));
        $ListeCom_a = $this->managers->getManagerOf('Comments')->getListByAuthor($this->app()->user()->getAttribute('id'));
            $listeComnews = $this->managers->getManagerOf('Comments')->getListByCommentAuthor($this->app()->user()->getAttribute('id'));



            if ($listeNews != NULL) {
                foreach ($listeNews as $news) {
                   $news->setLink('update', $this->page->getSpecificLink('News', 'update', array($news->id())));
                    $news->setLink('delete',$this->page->getSpecificLink('News', 'delete', array($news->id())));
                   $news->setLink('show',$this->page->getSpecificLink('News', 'show', array($news->id())));

                }
            }
            if ($ListeCom_a != NULL) {
                foreach ($ListeCom_a as $com) {
                 $com->setLink('update',$this->page->getSpecificLink('News', 'updateComment', array($com->id())));
                  $com->setLink('delete',$this->page->getSpecificLink('News', 'deleteComment', array($com->id())));
                    $com->setLink('show',$this->page->getSpecificLink('News', 'show', array($com->news())));

                }

            }
            if ($listeComnews != NULL) {
                foreach ($listeComnews as &$comnews) {

                    $new= new News([
                        'id' => $comnews['nid'],
                        'titre' => $comnews['titre']

                    ]);
                    var_dump($ListeCom_a);
//                    die();
                    foreach($ListeCom_a as $com)
                    {     var_dump($com->news());var_dump($new->id());

                        /** @var Comment $com */
                        if ($com->news() == $new->id())
                        {
                            $com->setNews($new);
                        }
                    }



                }

              //
            }

            $this->page->addVar('listeNews', $listeNews);
            $this->page->addVar('listeCom', $ListeCom_a);
            $this->page->addVar('log', $this->app()->user()->getAttribute('log'));


        $this->Build();


    }



    public function executeDeleteComment(HTTPRequest $request)
    {
                $this->RedirectComments404($request);

                $news=$this->managers->getManagerOf('Comments')->getNewsbyCommentId($request->getData('id'));
                $this->managers->getManagerOf('Comments')->delete($request->getData('id'));

                $this->app->user()->setFlash('Le commentaire a bien été supprimé !');

         //$this->page->getSpecificLink('News', 'show', array($news));
        $this->app->httpResponse()->redirect( $this->page->getSpecificLink('News', 'show', array($news)));


        $this->Build();

    }

    public function executeUpdateComment(HTTPRequest $request)
    {
        $this->RedirectComments404($request);

            $this->page->addVar('title', 'Modification d\'un commentaire');
        $news=$this->managers->getManagerOf('Comments')->getNewsbyCommentId($request->getData('id'));


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

                    $this->app->httpResponse()->redirect( $this->page->getSpecificLink('News', 'show', array($news)));
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
     $log = $this->app->user()->getAttribute('log');
            $usr= $this->app->user()->getAttribute('id');


            $news = new News([
                'auteur' => $log,
                'titre' => $request->postData('titre'),
                'contenu' => $request->postData('contenu'),
                'user' =>  $usr

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

            $auteur = $this->managers->getManagerOf('Users')->get($request->getData('id'));
        var_dump($auteur);



        if ($auteur != NULL) {
            $ListCom = $this->managers->getManagerOf('Comments')->getListByAuthor($auteur->id());
            $listenews = $this->managers->getManagerOf('News')->getListByAuthor($auteur->id());

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

                    if (!($this->app()->user()->isUser()) && !($this->app()->user()->isAuthenticated()))
                    {
                        $this->app()->user()->setFlash('Veuillez vous connecter');
                    }
                    elseif (!($this->app()->user()->isAuthenticated())) {

                        $this->app()->user()->setFlash('Accès interdit');
                        $this->app()->httpResponse()->redirect('/');

                    }
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
        // Get value of clicked button
        // $this->page()->setIshtml();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $news= $request->postData('news');
            $authors = $this->managers->getManagerOf('Users')->getAuthorUsingNewsComments($news);





            if ($this->app->user()->isUser() == true || $this->app->user()->isAuthenticated() == true) {
                $comment = new Comment([
                    'news' => $news,
                    'auteur' => $this->app->user()->getAttribute('log'),
                    'contenu' => $request->postData('contenu'),
                    'email' => $this->app->user()->getAttribute('mail'),
                    'user' =>  $this->app->user()->getAttribute('id'),

                ]);
                $email= $this->app->user()->getAttribute('mail');


            } else {
                $comment = new Comment([
                    'news' => $news,
                    'auteur' => $request->postData('auteur'),
                    'contenu' => $request->postData('contenu'),
                    'email' => $request->postData('email'),

                ]);
                $email=$request->postData('email');
            }

            if (((filter_var($email, FILTER_VALIDATE_EMAIL)) == false ) && ($request->postData('email') != ''))
            {
                $msg =false;
                $this->page()->addVar('raison','Email invalide');

            }
            elseif(($request->postExists('auteur')) &&($this->managers()->getManagerOf('Users')->getUser($request->postData('auteur'))))

            {
                $msg=false;
                $this->page()->addVar('raison','Nom d\'auteur non disponible');
            }
            else
            {
                $id = $request->postData('com');

                $formBuilder = new CommentFormBuilder($comment);
                if ($this->app->user()->isUser() == true || $this->app->user()->isAuthenticated() == true) {

                    $formBuilder->buildUser();
                } else {

                    $formBuilder->build();
                }
                $form = $formBuilder->form();

                $formHandler = new FormHandler($form, $this->managers->getManagerOf('Comments'), $request);

                if ($formHandler->process())
                {
                    // $this->sendmail($request->postData('news'),$email);
                    $msg=true;
                }

                $comments = $this->managers->getManagerOf('Comments')->getListAfterIdRefresh($id,$news);


                if ($comments != NULL) {
                    if ($this->app->user()->isUser() == true || $this->app->user()->isAuthenticated() == true)
                    {
                        $etat=$this->app->user()->getAttribute('state');
                    }
                    else
                    {
                        $etat=0;
                    }
                    foreach ($comments as $com) {


                     $com->setLink('update',$this->page->getSpecificLink('News', 'updateComment', array($com->id())));
                      $com->setLink('delete',$this->page->getSpecificLink('News', 'deleteComment', array($com->id())));
                        $com->setLink('user','');

                        if ($authors != NULL) {

                            foreach ($authors as $auth) {
                                if ($auth->login() == $com['auteur']) {

                                    $com->setLink('user',$this->page->getSpecificLink('News', 'showuser', array($auth->id())));
                                }
                            }

                        }

                        $tableau[$com->id()]= array(
                            "auteur" => $com->auteur(),
                            "email" => $com->email(),
                            "contenu" => $com->contenu(),
                            "date" => $com->date()->format('d/m/Y à H\hi'),
                            "id" => $com->id(),
                            "news"=>$news,
                            "etat"=>$etat,
                            "link"  =>$com->link('user'),
                            "delete" =>$com->link('delete'),
                            "update" => $com->link('update') );
                    }
                    $this->page->addVar('comments', $tableau);
                }
            }
            $this->page->addVar('msg',$msg);


        }
        else
        {
            $this->app()->user()->setFlash('Accès interdit');

            $this->app()->httpResponse()->redirect('/');
        }

    }
    public function executeShowDynamic(HTTPRequest $request)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $count=0;


            $news= $request->postData('news');
            $com = $request->postData('com');
            $sens=$request->postData('sens');
            $authors = $this->managers->getManagerOf('Users')->getAuthorUsingNewsComments($news);

if ($sens =='bottom') {
    $comments = $this->managers->getManagerOf('Comments')->getListAfterIdScroll($com, $news);
}elseif ($sens=='top')
{
    $comments=$this->managers->getManagerOf('Comments')->getListAfterIdRefresh($com,$news);}


            if ($comments != NULL) {
                if ($this->app->user()->isUser() == true || $this->app->user()->isAuthenticated() == true)
                {
                    $etat=$this->app->user()->getAttribute('state');
                }
                else
                {
                    $etat=0;
                }
                foreach ($comments as $com) {
                 $com->setLink('update',$this->page->getSpecificLink('News', 'updateComment', array($com->id())));
                  $com->setLink('delete',$this->page->getSpecificLink('News', 'deleteComment', array($com->id())));
                    $com->setLink('user','');

                    if ($authors != NULL) {

                        foreach ($authors as $auth) {
                            if ($auth->login() == $com['auteur']) {

                                $com->setLink('user',$this->page->getSpecificLink('News', 'showuser', array($auth->id())));
                            }
                        }

                    }

                    $tableau[$com->id()]= array(
                        "auteur" => $com->auteur(),
                        "email" => $com->email(),
                        "contenu" => $com->contenu(),
                        "date" => $com->date()->format('d/m/Y à H\hi'),
                        "id" => $com->id(),
                        "news"=>$news,
                        "etat"=>$etat,
                        "link"  =>$com->link('user'),
                        "delete" =>$com->link('delete'),
                        "update" => $com->link('update') );
                    $count= $count + 1;
                }
                $this->page->addVar('comments', $tableau);
            }
            $this->page->addVar('nb',$count);

        }
        else
        {
            $this->app()->user()->setFlash('Accès interdit');

            $this->app()->httpResponse()->redirect('/');
        }
    }
    public function executeCheck(HTTPRequest $request)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        if ($request->postExists('com')) {
            $id = $request->postData('com');
            if ($this->managers()->getManagerOf('Comments')->get($id))
            {
                $check=1;

            }
            else
            {
                $check=0;
            }
            $this->page()->addVar('check',$check);
        }
    }
    else
    {
        $this->app()->user()->setFlash('Accès interdit');

        $this->app()->httpResponse()->redirect('/');
    }

    }
    public function executeTest2(HTTPRequest $request)
    {
        // Get value of clicked button
        // $this->page()->setIshtml();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $news= $request->postData('news');





            if ($this->app->user()->isUser() == true || $this->app->user()->isAuthenticated() == true) {
                $comment = new Comment([
                    'news' => $news,
                    'auteur' => $this->app->user()->getAttribute('log'),
                    'contenu' => $request->postData('contenu'),
                    'email' => $this->app->user()->getAttribute('mail'),
                    'user' =>  $this->app->user()->getAttribute('id'),

                ]);
                $email= $this->app->user()->getAttribute('mail');


            } else {
                $comment = new Comment([
                    'news' => $news,
                    'auteur' => $request->postData('auteur'),
                    'contenu' => $request->postData('contenu'),
                    'email' => $request->postData('email'),

                ]);
                $email=$request->postData('email');
            }

            if (((filter_var($email, FILTER_VALIDATE_EMAIL)) == false ) && ($request->postData('email') != ''))
            {
                $msg =false;
                $this->page()->addVar('raison','Email invalide');

            }
            elseif(($request->postExists('auteur')) &&($this->managers()->getManagerOf('Users')->getUser($request->postData('auteur'))))

            {
                $msg=false;
                $this->page()->addVar('raison','Nom d\'auteur non disponible');
            }
            else
            {
                $id = $request->postData('com');

                $formBuilder = new CommentFormBuilder($comment);
                if ($this->app->user()->isUser() == true || $this->app->user()->isAuthenticated() == true) {

                    $formBuilder->buildUser();
                } else {

                    $formBuilder->build();
                }
                $form = $formBuilder->form();

                $formHandler = new FormHandler($form, $this->managers->getManagerOf('Comments'), $request);

                if ($formHandler->process())
                {
                    // $this->sendmail($request->postData('news'),$email);
                    $msg=true;
                }
                $authors = $this->managers->getManagerOf('Users')->getAuthorUsingNewsComments($news);


                $comments = $this->managers->getManagerOf('Comments')->getListAfterIdRefresh($id,$news);


                if ($comments != NULL) {
                    if ($this->app->user()->isUser() == true || $this->app->user()->isAuthenticated() == true)
                    {
                        $etat=$this->app->user()->getAttribute('state');
                    }
                    else
                    {
                        $etat=0;
                    }
                    foreach ($comments as $com) {



                        $com->setLink('update',$this->page->getSpecificLink('News', 'updateComment', array($com->id())));
                        $com->setLink('delete',$this->page->getSpecificLink('News', 'deleteComment', array($com->id())));

                        if ($authors != NULL) {
                            foreach ($authors as $auth) {
                                if ($auth->login() == $com['auteur']) {
                                    $com->setLink('user',$this->page->getSpecificLink('News', 'showuser', array($auth->id())));
                                }
                            }

                        }


                    }
                    $this->page->addVar('comments', $comments);
                }
            }
            $this->page->addVar('msg',$msg);
            if($this->app()->user()->getAttribute('id') != NULL) {
                $usr=$this->app()->user()->getAttribute('id');
            }
            else
            {
                $usr='';
            }
            $this->page()->addVar('id',$usr);


        }
        else
        {
            $this->app()->user()->setFlash('Accès interdit');

            $this->app()->httpResponse()->redirect('/');
        }

    }

}