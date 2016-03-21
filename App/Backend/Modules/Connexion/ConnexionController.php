<?php
namespace App\Backend\Modules\Connexion;

use Entity\OutsideUser;
use \OCFram\BackController;
use \OCFram\HTTPRequest;

class ConnexionController extends BackController
{
    public function executeIndex(HTTPRequest $request)
    {
       $this->connect = true;
        $this->Build();


            $this->page->addVar('title', 'Connexion');

        if ($request->postExists('login')) {
            $login = $request->postData('login');
            $password = $request->postData('password');

            //var_dump($this->managers->getManagerOf('Users')->getUser($login));  ;
            if ($this->managers->getManagerOf('Users')->getUser($login) != NULL)
            {

                if ($login == $this->managers->getManagerOf('Users')->getUser($login)->login() && crypt($password,'BDD') == ($this->managers->getManagerOf('Users')->getUser($login)->password())) {
                    if ($this->app->user()->isUser() == false) {
                        $this->app->user()->setAuthenticated(true);


                        $this->managers->getManagerOf('Users')->getUser($login)->setAttribute('id', $this->managers->getManagerOf('Users')->getUser($login)->id());
                        $this->managers->getManagerOf('Users')->getUser($login)->setAttribute('log', $this->managers->getManagerOf('Users')->getUser($login)->login());
                        $this->managers->getManagerOf('Users')->getUser($login)->setAttribute('mail', $this->managers->getManagerOf('Users')->getUser($login)->email());
                        $this->managers->getManagerOf('Users')->getUser($login)->setAttribute('state', $this->managers->getManagerOf('Users')->getUser($login)->state());


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

    }
    public function executeLogout (HTTPRequest $request)
    {


            $this->page->addVar('title', 'Deconnexion');


            session_unset();
            session_destroy();
            session_start();
            $this->app->user()->setAuthenticated(false);
            $this->app->user()->setFlash('Deconnexion reussie');
            $this->app->httpResponse()->redirect('..');




        $this->Build();
    }

}