<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\User;

class Home extends DefaultPage
{

    /**
     * Método responável por retornar o conteudo do home
     * @return string
     */
    public static function getHome()
    {
        $user = new User;

        //View da pagina home
        $content = View::render('pages/home', [
            'name' => $user->id,
            'description' => $user->nome,
            'teste' => $user->cpf
        ]);

        //Retorna a view da pagina
        return parent::getDefaultPage('Game Store', $content);
    }
}
