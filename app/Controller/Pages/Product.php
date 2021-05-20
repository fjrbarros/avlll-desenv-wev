<?php

namespace App\Controller\Pages;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use \App\Utils\View;
use \App\Controller\Pages;
use \App\Model\Entity\User as EntityUser;

class Product extends DefaultPage
{
    public static function getProduct()
    {
         //View da pagina cadastro de produto
         $content = View::render('pages/product/form', [
             'title' => 'Cadastro de produto',
             'msg-error-alert' => '',
             'nome' => '',
             'descricao' => '',
             'valor' => ''
         ]);

        //Retorna a view da pagina
        return parent::getDefaultPage($userData['title'] ?? 'Cadastro de produto', $content);
    }
}
