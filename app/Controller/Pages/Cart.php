<?php

namespace App\Controller\Pages;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use \App\Utils\View;
use \App\Model\Entity\User;
use \App\Model\Entity\Product;
use \App\Model\Entity\Pedido;
use \App\Model\Entity\PedidoProduto;

class Cart extends DefaultPage
{
    public static function getCart()
    {
        if (!User::isLoged()) {
            return self::getPageError('Usuário não autenticado!');
        }

        $content = '';
        $total = 0;
        $btnSalvar = '';

        if (isset($_SESSION['PRODUCT_LIST'])) {
            foreach ($_SESSION['PRODUCT_LIST'] as $sessionProduct) {

                $produto = new Product();

                $produto->id = $sessionProduct['ID_PRODUCT'];

                $produto = $produto->getProductId();

                if ($produto instanceof Product) {
                    $content .= View::render('pages/cart/tr', [
                        'nome' => $produto->nome,
                        'quantidade' => $sessionProduct['QTDE'],
                        'valor' => $produto->valor * $sessionProduct['QTDE'],
                        'btn-remove' => View::render('pages/cart/btnRemove', ['idProductSession' => $produto->id])
                    ]);

                    $total += $produto->valor * $sessionProduct['QTDE'];
                }
            }

            $content .= View::render('pages/cart/tr', [
                'nome' => '',
                'quantidade' => '<strong>Valor total</strong>',
                'valor' => '<strong>' . $total . '</strong>',
                'btn-remove' => ''
            ]);

            $btnSalvar =  View::render('pages/cart/btnSalvarPedido', ['valor-total' => $total]);
        }

        $content = View::render('pages/cart/cart', [
            'content' => $content,
            'btn-salvar' => $btnSalvar
        ]);

        return parent::getDefaultPage('Cart', $content);
    }

    public static function getPageError($text)
    {
        $content = View::render('pages/error', [
            'content' => $text
        ]);

        return parent::getDefaultPage('Error', $content);
    }

    public static function removeItemCart($request, $idProdutoSession)
    {
        if ($request->getHttpMethod() === 'GET') {
            $produto = new Product();

            $produto->id = $idProdutoSession;

            $produto = $produto->getProductId();

            if ($produto instanceof Product) {
                if (isset($_SESSION['PRODUCT_LIST'])) {
                    $content = View::render('pages/question', [
                        'content' => 'Deseja remover o produto ' . $produto->nome . '?',
                        'name' => 'Excluir',
                        'icon' => 'fa-trash-alt'
                    ]);
                }
            }
            return parent::getDefaultPage('Atenção', $content ?? 'Erro ao recuperar o produto!');
        }

        if ($request->getHttpMethod() === 'POST') {
            if (isset($_SESSION['PRODUCT_LIST'])) {
                unset($_SESSION['PRODUCT_LIST']['ID_PRODUCT_' . $idProdutoSession]);
                header('location: ' . URL . '/cart');
                exit;
            }
        }
    }

    public static function salvarPedido($request)
    {
        $postvars = $request->getPostVars();

        $pedido = new Pedido();

        $pedido->valor_total = $postvars['valor-total'];

        if ($pedido->cadastrar()) {
            return self::cadastrarPedidoProduto($pedido->id);
        } else {
            return self::getPageError('Erro no cadastro do pedido!');
        }
    }

    public static function cadastrarPedidoProduto($idPedido)
    {
        $error = '';

        foreach ($_SESSION['PRODUCT_LIST'] as $sessionProduct) {

            $pedidoProduto = new PedidoProduto();

            $pedidoProduto->id_pedido = $idPedido;
            $pedidoProduto->id_usuario = User::getIdUser();
            $pedidoProduto->id_produto = $sessionProduct['ID_PRODUCT'];

            if (!$pedidoProduto->cadastrar()) {
                $error .= 'Erro ao salvar o produto da lista, ID = ' . $sessionProduct['ID_PRODUCT'];
            }
        }

        if (strlen($error)) {
            return self::getPageError($error);
        }

        unset($_SESSION['PRODUCT_LIST']);

        $content = View::render('pages/success', [
            'content' => 'Pedido cadastrado com sucesso!'
        ]);

        return parent::getDefaultPage('Sucesso', $content);
    }
}
