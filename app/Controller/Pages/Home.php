<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Product as EntityProduct;
use \App\Model\Entity\User;

class Home extends DefaultPage
{
    // Método responável por retornar o conteudo do home
    public static function getHome()
    {
        $newProduct = new EntityProduct();

        $result = $newProduct->getProducts();

        $content = '';

        foreach ($result as $product) {

            if (!strlen($product->arquivo)) {
                $dir = '/mvc/app/Files/not-available.jpg';
            } else {
                $dir = __DIR__ . '/../../Files/' . $product->arquivo;

                if (file_exists($dir)) {
                    $dir = '/mvc/app/Files/' . $product->arquivo;
                } else {
                    $dir = '/mvc/app/Files/not-available.jpg';
                }
            }

            $content .= View::render('pages/home/card', [
                'nome' => $product->nome ?? '',
                'descricao' => $product->descricao ?? '',
                'valor' => number_format($product->valor, 2, ",", ".") ?? '',
                'idProduct' => $product->id,
                'dir-img' =>  $dir,
                'actions' => self::getActions($product)
            ]);
        }

        $home = View::render('pages/home/home', [
            'content' => $content ?? ''
        ]);

        //Retorna a view da pagina
        return parent::getDefaultPage('Game Store', $home);
    }

    public static function getActions($product)
    {
        return User::isLoged() ? View::render('pages/home/actions', ['idProduct' => $product->id]) : '';
    }
}
