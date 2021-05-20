<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\User;
use \App\Model\Entity\Product as EntityProduct;

class Home extends DefaultPage
{

    /**
     * Método responável por retornar o conteudo do home
     * @return string
     */
    public static function getHome()
    {
        $user = new User;

        $newProduct = new EntityProduct();

        $result = $newProduct->getProducts();

        $content = '';

        foreach ($result as $product) {
            $content .= View::render('pages/home/card', [
                'nome' => $product->nome ?? '',
                'descricao' => $product->descricao ?? '',
                'valor' => number_format($product->valor, 2, ",", ".") ?? '',
                'actions' => self::getActions($product)
            ]);
        }

        $home = View::render('pages/home/home', [
            'content' => $content ?? ''
        ]);

        // number_format($produto->valor, 2, ",", ".")

        // echo "<pre>";
        // print_r($result);
        // echo "</pre>";
        // exit;

        //View da pagina home
        // $content = View::render('pages/home', [
        //     'name' => $user->id,
        //     'description' => $user->nome,
        //     'teste' => $user->cpf
        // ]);

        //Retorna a view da pagina
        return parent::getDefaultPage('Game Store', $home);
    }

    public static function getActions($product)
    {
        return isset($_SESSION['ADM']) ? View::render('pages/home/actions', ['idProduct' => $product->id]) : '';
    }
}
