<?php
/**
 * Created by PhpStorm.
 * User: alamrous
 * Date: 15/03/2016
 * Time: 13:03
 */

namespace OCFram;


use Composer\DependencyResolver\Request;
use OCFram\BackController;
use OCFram\HTTPRequest;
trait Centrale
{     protected $nav = '';
    protected $links =[];
    protected $cookie;
    protected $IsCookie = false ;
    protected $connect = false ;


    protected function addNav(array $navs)
    {
        foreach ($navs as $key => $nav)
        {
        $this->nav .= '<li> <a href ="' . $nav . '">' . $key . '</a></li>';
        }
    }
    protected function BuildMenu()
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
        $this->page->addVar('menu',$this->nav);

}
    public function Build()
    {
        if ($this->connect == true)
        {
            $this->RedirectConnect();
        }
        if ($this->IsCookie == true )
        {
            $this->setCookie($request);
        }
        $this->BuildMenu();

    }


    protected function RedirectConnect()
    {
        if (($this->app()->name()=='Backend') && ($this->app()->user()->isUser()))
        {
            $this->app()->user()->setFlash('Accès interdit');

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
    protected function addLinks(array $links)
    {
        array_merge($this->links, $links);
    }
    protected function setCookie(HTTPRequest $request)
    {
       $this->cookie =$request->cookieData('cookie');
    }
    protected function getCookie()
    {
        return $this->cookie;
    }

    public function setConnect($connect = true )
    {
        $this->connect = $connect;
    }

    public function setIsCookie($IsCookie = true)
    {
        $this->IsCookie = $IsCookie;
    }
}

