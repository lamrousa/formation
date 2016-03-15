<?php
/**
 * Created by PhpStorm.
 * User: alamrous
 * Date: 15/03/2016
 * Time: 13:03
 */

namespace OCFram;


use OCFram\BackController;
use OCFram\HTTPRequest;
class Centrale
{     protected $nav = '';


    public function addNav(array $navs)
    {
        foreach ($navs as $key => $nav)
        {
        $this->nav .= '<li> <a href ="' . $nav . '">' . $key . '</a></li>';
        }
    }
    public function BuildMenu(Application $app,Page $page)
    {
      $page->getLinks();
        extract ($page->getVars());

        if ($app->user()->isUser() && $app->name() == 'Frontend' ) {
            $navs = array('Accueil' => $Newsindex , 'Ajouter News' => $Newsinsert, 'Mes News' => $Newsmynews,'Deconnexion' => $Connexionlogout );
    }
        elseif(($app->user()->isAuthenticated()) && ($app->name() == 'Backend'))
        {
            $navs = array('Admin' => $adminNewsindex , 'Deconnexion' => $adminConnexionlogout, 'Ajouter une news ' => $adminNewsinsert );

        }

        elseif ($app->name() == 'Frontend' && !($app->user()->isAuthenticated()) && !($app->user()->isUser()) )
        {
             $navs = array('Accueil' => $Newsindex , 'Inscription' => $Connexionsignup, 'Connexion' => $Connexionlogin);
        }
        elseif ($app->name() == 'Backend'  && !($app->user()->isAuthenticated()) && !($app->user()->isUser()))
        {
            $navs = array('Accueil' => '/');
        }
        elseif ($app->name() == 'Frontend'&& $app->user()->isAuthenticated() ) {
            $navs = array('Admin_Mode' => '/admin/');
        }


        $this->addNav($navs);
        return $this->nav;
}
public function RedirectNews404 (Application $app, Managers $managers,HTTPRequest $request)
{
    if (($request->getExists('id') == TRUE) && ($managers->getManagerOf('News')->getUnique($request->getData('id')) == NULL)) {
        if ($app->name() == 'Frontend') {
            $app->user()->setFlash('Accès interdit');
            $app->httpResponse()->redirect('/');
        }
        else
        {
            $app->user()->setFlash('Accès interdit');
            $app->httpResponse()->redirect('/admin/');

        }
    }
    elseif (($managers->getManagerOf('News')->getUnique($request->getData('id')) != NULL ) && ($request->getExists('id') == TRUE ))
    {
        if ($app->name() == 'Frontend') {
            if (!($app->user()->isUser()) || ($app->user()->getAttribute('log') != $managers->getManagerOf('News')->getUnique($request->getData('id'))->auteur())) {
                if (!($app->user()->isUser()))
                {
                    $app->user()->setFlash('Veuillez vous connecter');
                }
                else {
                    $app->user()->setFlash('Accès interdit');

                }
                $app->httpResponse()->redirect('/');
            }

        } elseif  ($app->name() == 'Backend') {
            if (!($app->user()->isAuthenticated()))
            {
                $app->httpResponse()->redirect('/admin/');
            }

        }

    }
elseif( !($app->user()->isUser()) && ($request->getExists('id') != true ) && $app->name()=='Frontend')
{
    $app->user()->setFlash('Veuillez vous connecter');

    $app->httpResponse()->redirect('/');

}

}
    public function RedirectComments404 (Application $app, Managers $managers,HTTPRequest $request)
    {
        if (($request->getExists('id') == TRUE) && ($managers->getManagerOf('Comments')->get($request->getData('id')) == false)) {

            if ($app->name() == 'Frontend') {
                $app->user()->setFlash('Accès interdit');
            $app->httpResponse()->redirect('/');
        }
            else
            {
                $app->user()->setFlash('Accès interdit');
                $app->httpResponse()->redirect('/admin/');

            }



        }
        elseif (($managers->getManagerOf('Comments')->get($request->getData('id')) != false) && ($request->getExists('id') == TRUE ))
        {
            if ($app->name() == 'Frontend') {

                if (!($app->user()->isUser()) || ($app->user()->getAttribute('log') != $managers->getManagerOf('Comments')->get($request->getData('id'))->auteur())) {

                    if (!($app->user()->isUser()))
                    {
                        $app->user()->setFlash('Veuillez vous connecter');
                    }
                    else {
                        $app->user()->setFlash('Accès interdit');

                    }
                    $app->httpResponse()->redirect('/');
                }

            } elseif  ($app->name() == 'Backend') {
                if (!($app->user()->isAuthenticated()))
                {
                    $app->httpResponse()->redirect('/admin/');
                }

            }

        }

    }

    public function RedirectConnect(Application $app)
    {
        if (($app->name()=='Backend') && ($app->user()->isUser()))
        {
            $app->httpResponse()->redirect('/');

        }
        if(($app->name()=='Frontend') && ($app->user()->isAuthenticated()))
        {
            $app->httpResponse()->redirect('/admin/');

        }
        if (($app->name()=='Frontend') && ($app->user()->isUser()))
        {
            $app->user()->setFlash('Vous êtes déjà connecté');
            $app->httpResponse()->redirect('/');
        }
        if (($app->name()=='Backend') && ($app->user()->isAuthenticated()))
        {
            $app->user()->setFlash('Vous êtes déjà connecté');
            $app->httpResponse()->redirect('/admin/');
        }

    }


}

