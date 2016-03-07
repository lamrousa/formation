<?php
/**
 * Created by PhpStorm.
 * User: alamrous
 * Date: 04/03/2016
 * Time: 12:42
 */

namespace App\Backend\Modules\Connexion;


use OCFram\BackController;
use OCFram\HTTPRequest;

class ConnexionController extends BackController
{
public function executeIndex(HTTPRequest $request)
{
    $this->page->addvar('title','Connexion');
    if ($request->postExists('login'))
    {
        $login= $request->postData('login') ;
        $password = $request->postData('password');

        if ($login == $this->app->config()->get('login') && $password == $this->app->config()->get('pass'))
        {
            $this->app->user()->setAuthenticated(true) ;
            $this->app->httpResponse()->redirect('.');
        }
        else
        {
            $this->app->user()->setFlash('Le pseudo ou le mdp est incorrect');
        }
    }
}
}