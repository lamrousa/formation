<?php
namespace App\Frontend\Modules\Connexion;

use Entity\OutsideUser;
use \OCFram\BackController;
use \OCFram\HTTPRequest;
use OCFram\Centrale;

class ConnexionController extends BackController

{
    public function executeSignup (HTTPRequest $request)
    {
        $center = new Centrale();
        $center->RedirectConnect($this->app());

        $this->page->addVar('title','signin');
        if ($request->postExists('slogin'))
        {
            $AUC_login = $request->postData('slogin');
            $AUC_password = $request->postData('spassword');
            $AUC_email = $request->postData('semail');
            $confirmation = $request->postData('spassword2');

            if (filter_var($AUC_email, FILTER_VALIDATE_EMAIL))
        {
            if ($AUC_password == $confirmation)
            {
            var_dump($AUC_login);var_dump(crypt('mdp','BDD'));  var_dump($AUC_email) ;
        if ($this->managers->getManagerOf('Users')->getUser($AUC_login) == NULL )
        {
            $user = new OutsideUser;
            $user->setLogin($AUC_login);
            $user->setPassword($AUC_password);
            $user->setEmail($AUC_email);

            $this->managers->getManagerOf('Users')->addUser($user);
            $this->page->addVar('OutUser',$user);
            $this->app->user()->setFlash('Inscription reussie ! Bienvenue,'.$AUC_login);
            $this->app->user()->setIsUser(true);

            $this->managers->getManagerOf('Users')->getUser($AUC_login)->setAttribute('id', $this->managers->getManagerOf('Users')->getUser($AUC_login)->id());
            $this->managers->getManagerOf('Users')->getUser($AUC_login)->setAttribute('log', $this->managers->getManagerOf('Users')->getUser($AUC_login)->login());
            $this->managers->getManagerOf('Users')->getUser($AUC_login)->setAttribute('mail', $this->managers->getManagerOf('Users')->getUser($AUC_login)->email());

            $this->app->httpResponse()->redirect('.');

        }
            else {
                $this->app->user()->setFlash('Login déjà utilisé');

            }
        }
            else $this->app->user()->setFlash('Les deux mots de passe ne correspondent pas ');

        }
            else $this->app->user()->setFlash('L\'email est invalide');
        }


        $this->page->addVar('menu',$center->BuildMenu($this->app(),$this->page()));

    }
    public function executeLogin (HTTPRequest $request)
    {
        $center = new Centrale();
        $center->RedirectConnect($this->app());

        $this->page->addVar('title','login');
        if ($request->postExists('llogin')) {
            $login = $request->postData('llogin');
            $password = $request->postData('lpassword');

            //var_dump($this->managers->getManagerOf('Users')->getUser($login)); die();
            if ($this->managers->getManagerOf('Users')->getUser($login) != NULL)
            {

                if ($login == $this->managers->getManagerOf('Users')->getUser($login)->login() && crypt($password,'BDD') == ($this->managers->getManagerOf('Users')->getUser($login)->password())) {
                    if ($this->app->user()->isAuthenticated() == false) {
                        $this->app->user()->setIsUser(true);


                        $this->managers->getManagerOf('Users')->getUser($login)->setAttribute('id', $this->managers->getManagerOf('Users')->getUser($login)->id());
                        $this->managers->getManagerOf('Users')->getUser($login)->setAttribute('log', $this->managers->getManagerOf('Users')->getUser($login)->login());
                       $this->managers->getManagerOf('Users')->getUser($login)->setAttribute('mail', $this->managers->getManagerOf('Users')->getUser($login)->email());


                        $this->app->user()->setFlash('Connexion Reussie');
                        $this->app->httpResponse()->redirect('.');

                    }

                }
                else {
                    $this->app->user()->setFlash('login ou mdp incorrect');

                }
            }
            else {
                $this->app->user()->setFlash('login ou mdp incorrect');
            }
        }

        $this->page->addVar('menu',$center->BuildMenu($this->app(),$this->page()));

    }


   public function executeLogout (HTTPRequest $request)
{
    $this->page->addVar('title','DeleteUser');
    if ($this->app->user()->isUser() == true )
    {
        $this->page->addVar('title','Deconnexion');
        session_unset();
        session_destroy();
        session_start();
        $this->app->user()->setIsUser(false);
        $this->app->user()->setFlash('Deconnexion reussie');
        $this->app->httpResponse()->redirect('.');
    }
    $center = new Centrale();

    $this->page->addVar('menu',$center->BuildMenu($this->app(),$this->page()));
}
}