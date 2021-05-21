<?php

namespace App\Controller\Pages;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use \App\Utils\View;

class DefaultPage
{

    // Responsavel por retornar o header da pagina
    private static function getHeader()
    {
        return View::render('includes/header', [
            'login-logout' => self::getBtnLoginLogout()
        ]);
    }

    // Responsavel por renderizar opção de login ou logout
    public static function getBtnLoginLogout()
    {
        if (isset($_SESSION['lOGADO'])) {
            return View::render('includes/logout');
        }

        return View::render('includes/login');
    }


    // Responsavel por retornar o footer da pagina
    private static function getFooter()
    {
        return View::render('includes/footer');
    }


    // Método responável por retornar o conteudo da pagina generica
    public static function getDefaultPage($title, $content)
    {
        return View::render('pages/defaultPage', [
            'title' => $title,
            'header' => self::getHeader(),
            'content' => $content,
            'footer' => self::getFooter(),
        ]);
    }
}
