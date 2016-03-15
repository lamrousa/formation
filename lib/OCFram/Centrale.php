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
trait Centrale
{     protected $nav = '';
    protected $links =[];


    public function addNav(array $navs)
    {
        foreach ($navs as $key => $nav)
        {
        $this->nav .= '<li> <a href ="' . $nav . '">' . $key . '</a></li>';
        }
    }
    public function BuildMenu()
    {
      $this->page()->getLinks();
        extract ($this->page()->getVars());

        if ($this->app()->user()->isUser() && $this->app()->name() == 'Frontend' ) {
            $navs = array('Accueil' => $Newsindex , 'Ajouter News' => $Newsinsert, 'Mes News' => $Newsmynews,'Deconnexion' => $Connexionlogout );
    }
        elseif(($this->app()->user()->isAuthenticated()) && ($this->app()->name() == 'Backend'))
        {
            $navs = array('Admin' => $adminNewsindex , 'Deconnexion' => $adminConnexionlogout, 'Ajouter une news ' => $adminNewsinsert );

        }

        elseif ($this->app()->name() == 'Frontend' && !($this->app()->user()->isAuthenticated()) && !($this->app()->user()->isUser()) )
        {
             $navs = array('Accueil' => $Newsindex , 'Inscription' => $Connexionsignup, 'Connexion' => $Connexionlogin);
        }
        elseif ($this->app()->name() == 'Backend'  && !($this->app()->user()->isAuthenticated()) && !($this->app()->user()->isUser()))
        {
            $navs = array('Accueil' => '/');
        }
        elseif ($this->app()->name() == 'Frontend'&& $this->app()->user()->isAuthenticated() ) {
            $navs = array('Admin_Mode' => '/admin/');
        }

       $navs=array_merge($navs,$this->links);
        $this->addNav($navs);
        return $this->nav;
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

    public function RedirectConnect()
    {
        if (($this->app()->name()=='Backend') && ($this->app()->user()->isUser()))
        {
            $this->app()->httpResponse()->redirect('/');

        }
        if(($this->app()->name()=='Frontend') && ($this->app()->user()->isAuthenticated()))
        {
            $this->app()->httpResponse()->redirect('/admin/');

        }
        if (($this->app()->name()=='Frontend') && ($this->app()->user()->isUser()))
        {
            $this->app()->user()->setFlash('Vous êtes déjà connecté');
            $this->app()->httpResponse()->redirect('/');
        }
        if (($this->app()->name()=='Backend') && ($this->app()->user()->isAuthenticated()))
        {
            $this->app()->user()->setFlash('Vous êtes déjà connecté');
            $this->app()->httpResponse()->redirect('/admin/');
        }

    }
    public function addLinks(array $links)
    {
        array_merge($this->links, $links);
    }

}

