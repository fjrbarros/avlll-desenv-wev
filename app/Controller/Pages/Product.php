<?php

namespace App\Controller\Pages;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use \App\Utils\View;
use \App\Controller\Pages;
use \App\Model\Entity\Product as EntityProduct;

class Product extends DefaultPage
{
    public static function getProduct($productData = [])
    {
         //View da pagina cadastro de produto
         $content = View::render('pages/product/form', [
             'title' => $productData['title'] ?? 'Cadastro de produto',
             'msg-error-alert' => $productData['msg'] ?? '',
             'nome' => $productData['nome'] ?? '',
             'descricao' => $productData['descricao'] ?? '',
             'valor' => $productData['valor'] ?? ''
         ]);

        //Retorna a view da pagina
        return parent::getDefaultPage($userData['title'] ?? 'Cadastro de produto', $content);
    }

    public static function saveProduct($request)
    {
        $postVars = $request->getPostVars();

        $product = new EntityProduct();

        $product->nome = $postVars['nome'] ?? '';
        $product->descricao = $postVars['descricao'] ?? '';
        $product->valor = $postVars['valor'] ?? '';

        $errors = $product->validaDados();

        if(strlen($errors)) {
            return self::getProduct([
                'title' => 'Cadastro de produto',
                'msg' => $errors,
                'nome' => $product->nome,
                'descricao' => $product->descricao,
                'valor' => $product->valor
            ]);
        }

        if($product->cadastrar()) {
            return self::getProduct([
                'msg' => $product->getMsgFormat('Produto cadastrado com sucesso!', 'alert-success')
            ]);
        }
    }
}
