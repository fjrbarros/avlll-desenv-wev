<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class DefaultPage
{
    /**
     * Responsavel por retornar o header da pagina
     */
    private static function getHeader()
    {
        return View::render('includes/header');
    }

    /**
     * Responsavel por retornar o footer da pagina
     */
    private static function getFooter()
    {
        return View::render('includes/footer');
    }

    /**
     * Método responável por retornar o conteudo da pagina generica
     * @return string
     */
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
