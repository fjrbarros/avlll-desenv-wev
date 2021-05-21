<?php

namespace App\Controller\Pages;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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

            $itemAdd = '';

            if (isset($_SESSION['PRODUCT_LIST']['ID_PRODUCT_' . $product->id])) {
                $itemAdd = 'item-add';
            }

            $content .= View::render('pages/home/card', [
                'nome' => $product->nome ?? '',
                'descricao' => $product->descricao ?? '',
                'valor' => number_format($product->valor, 2, ",", ".") ?? '',
                'idProduct' => $product->id,
                'qtd' => 1,
                'dir-img' =>  $dir,
                'actions' => self::getActions($product),
                'item-add' =>  $itemAdd
            ]);
        }

        $home = View::render('pages/home/home', [
            'content' => $content ?? ''
        ]);

        //Retorna a view da pagina
        return parent::getDefaultPage('Game Store', $home);
    }

    public static function addProduct($request)
    {
        $postVars = $request->getPostVars();

        $_SESSION['PRODUCT_LIST']['ID_PRODUCT_' . $postVars['idProduct']] = ['ID_PRODUCT' => $postVars['idProduct'], 'QTDE' => $postVars['quantidade']];

        return self::getHome();
    }

    public static function getActions($product)
    {
        return User::isLoged() ? View::render('pages/home/actions', ['idProduct' => $product->id]) : '';
    }
}
