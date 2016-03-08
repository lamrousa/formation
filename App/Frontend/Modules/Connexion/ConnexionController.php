<?php
namespace App\Frontend\Modules\Connexion;

use Entity\OutsideUser;
use \OCFram\BackController;
use \OCFram\HTTPRequest;

class ConnexionController extends BackController

{
    public function executeSignup (HTTPRequest $request)
    {
        $this->page->addVar('title','signin');
        if ($request->postExists('slogin'))
        {
            $login = $request->postData('slogin');
            $password = $request->postData('spassword');


        if ($this->managers->getManagerOf('Users')->getUser($login) == NULL )
        {
            $user = new OutsideUser($login,$password);

            $this->managers->getManagerOf('Users')->addUser($user);
            $this->page->addVar('OutUser',$user);
            $this->app->user()->setFlash('Inscription reussie');
        }
            else {
                $this->app->user()->setFlash('Login déjà utilisé');

            }
        }


    }
    public function executeLogin (HTTPRequest $request)
    {
        $this->page->addVar('title','login');
        if ($request->postExists('llogin'))
        {
            $login = $request->postData('llogin');
            $password = $request->postData('lpassword');

        if ($login==$this->managers->getManagerOf('Users')->getUser($login)->login() && $password==$this->managers->getManagerOf('Users')->getUser($login)->password())
            {
                if ( $this->app->user()->isAuthenticated() ==false )
                {
                    $this->app->user()->setIsUser(true);

                    $this->page->addVar('user',$this->managers->getManagerOf('Users')->getUser($login));

                    $this->app->user()->setFlash('Connexion Reussie');
                    $this->app->httpResponse()->redirect('.');

                }

            }
        }

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
}
}