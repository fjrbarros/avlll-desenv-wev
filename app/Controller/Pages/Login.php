<?php

namespace App\Controller\Pages;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use \App\Utils\View;
use \App\Controller\Pages;
use \App\Model\Entity\User as EntityUser;

class Login extends DefaultPage
{
    public static function getLogin()
    {
        if (isset($_SESSION['lOGADO'])) {
            return Pages\Home::getHome();
        }

        //View da pagina de login
        return View::render('pages/login/login', [
            'error' => '',
            'email' => '',
            'senha' => ''
        ]);
    }

    public static function fazerLogin($request)
    {

        $postVars = $request->getPostVars();

        $user = new EntityUser();

        $user->email = trim($postVars['email']);
        $user->senha = $postVars['senha'];

        $errors = $user->validaDadosLogin();

        if (strlen($errors)) {
            return View::render('pages/login/login', [
                'error' => $errors,
                'email' => $user->email ?? '',
                'senha' => $user->senha ?? ''
            ]);
        }

        return Pages\Home::getHome();
    }

    public static function getLogout()
    {
        $content = View::render('pages/question', [
            'content' => 'Deseja sair do sistema?',
            'name' => 'Sair',
            'icon' => 'fa-power-off'
        ]);

        return parent::getDefaultPage('Atenção', $content);
    }

    public static function fazerLogout()
    {
        session_unset();

        return Pages\Home::getHome();
    }
}
