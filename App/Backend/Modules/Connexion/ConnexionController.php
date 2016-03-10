<?php
namespace App\Backend\Modules\Connexion;

use Entity\OutsideUser;
use \OCFram\BackController;
use \OCFram\HTTPRequest;

class ConnexionController extends BackController
{
    public function executeIndex(HTTPRequest $request)
    {
        if ($this->app->user()->isUser()==false) {
            $this->page->addVar('title', 'Connexion');

            if ($request->postExists('login')) {
                $login = $request->postData('login');
                $password = $request->postData('password');

                if ($login == $this->app->config()->get('login') && $password == $this->app->config()->get('pass')) {

                    $this->app->user()->setAuthenticated(true);
                    $this->app->httpResponse()->redirect('.');
                } else {
                    $this->app->user()->setFlash('Le pseudo ou le mot de passe est incorrect.');
                }
            }
        }
        else {
            $this->app->user()->setFlash('Vous êtes déjà connectés en tant que User. Veuillez vous deconnecter');
            $this->app->httpResponse()->redirect404();

        }
    }
    public function executeLogout (HTTPRequest $request)
    {
        if ($this->app()->user()->isAuthenticated() == true )
        {

            $this->page->addVar('title', 'Deconnexion');


            session_unset();
            session_destroy();
            session_start();
            $this->app->user()->setAuthenticated(false);
            $this->app->user()->setFlash('Deconnexion reussie');
            $this->app->httpResponse()->redirect('..');


        }
    }

}