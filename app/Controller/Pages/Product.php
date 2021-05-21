<?php

namespace App\Controller\Pages;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use \App\Utils\View;
use \App\Model\Entity\Product as EntityProduct;
use \App\Model\Entity\User;

class Product extends DefaultPage
{
    public static function getProduct($productData = [])
    {
        if (!User::isLoged()) {
            return self::getPageError('Usuário não autenticado!');
        }

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

    public static function saveProduct($request, $idProduto)
    {
        $product = new EntityProduct();

        $postVars = $request->getPostVars();

        $product->id = $idProduto;
        $product->nome = $postVars['nome'] ?? '';
        $product->descricao = $postVars['descricao'] ?? '';
        $product->valor = $postVars['valor'] ?? '';
        $product->id_user = $_SESSION['USER_ID'];

        $msg = $product->validaDados();

        if (strlen($msg['error'])) {
            return self::getProduct([
                'title' => 'Cadastro de produto',
                'msg' => $msg['error'],
                'nome' => $product->nome,
                'descricao' => $product->descricao,
                'valor' => $product->valor
            ]);
        }

        $product->arquivo = isset($msg['nameFile']) ? $msg['nameFile'] : '';

        if ($idProduto) {
            if ($product->atualizar()) {
                return self::getProduct([
                    'msg' => $product->getMsgFormat('Produto editado com sucesso!', 'alert-success')
                ]);
            }
        } else {
            if ($product->cadastrar()) {
                return self::getProduct([
                    'msg' => $product->getMsgFormat('Produto cadastrado com sucesso!', 'alert-success')
                ]);
            }
        }
    }

    public static function getPageError($text)
    {
        $content = View::render('pages/error', [
            'content' => $text
        ]);

        return parent::getDefaultPage('Error', $content);
    }

    public static function removeProduct($request, $idProduto)
    {
        if (!isset($idProduto) || !is_numeric($idProduto)) {
            return self::getPageError('Id do produto não encontrado ou inválido!');
        }

        $product = new EntityProduct();

        $product->id = $idProduto;

        $product = $product->getProductId();

        if (!$product instanceof EntityProduct) {
            return self::getPageError('Não existe produto cadastrado com esse código!');
        }

        $user = new User();

        $user->id = $product->id_user;

        $user = $user->getUserId();

        if (!$user instanceof User) {
            return self::getPageError('Erro ao recuperar usuário!');
        }

        if (strtoupper($user->administrador) === 'S') {

            if (User::isAdm()) {
                if ($request->getHttpMethod() === 'GET') {
                    $content = View::render('pages/question', [
                        'content' => 'Deseja remover o produto ' . $product->nome . '?',
                        'name' => 'Excluir',
                        'icon' => 'fa-trash-alt'
                    ]);

                    return parent::getDefaultPage('Atenção', $content);
                }

                if ($request->getHttpMethod() === 'POST') {
                    if ($product->excluir()) {
                        self::removeProductSession($product->id);
                        $content = View::render('pages/success', [
                            'content' => 'Produto removido com sucesso!'
                        ]);

                        return parent::getDefaultPage('Sucesso', $content);
                    }
                }
            } else {
                return self::getPageError('Apenas usuário com perfil de ADMINISTRADOR pode remover esse produto!');
            }
        } else {
           if (User::isAdm() && !User::isCliente()) {
                return self::getPageError('Apenas usuário com perfil de CLIENTE pode remover esse produto!');
            } else {
                if ($request->getHttpMethod() === 'GET') {
                    $content = View::render('pages/question', [
                        'content' => 'Deseja remover o produto ' . $product->nome . '?',
                        'name' => 'Excluir',
                        'icon' => 'fa-trash-alt'
                    ]);

                    return parent::getDefaultPage('Atenção', $content);
                }

                if ($request->getHttpMethod() === 'POST') {
                    if ($product->excluir()) {
                        self::removeProductSession($product->id);
                        $content = View::render('pages/success', [
                            'content' => 'Produto removido com sucesso!'
                        ]);

                        return parent::getDefaultPage('Sucesso', $content);
                    }
                }
            }
        }
    }

    public static function removeProductSession($idProduto)
    {
        if (isset($_SESSION['PRODUCT_LIST'])) {
            unset($_SESSION['PRODUCT_LIST']['ID_PRODUCT_' . $idProduto]);
        }
    }

    public static function editProduct($request, $idProduto)
    {
        if (!isset($idProduto) || !is_numeric($idProduto)) {
            return self::getPageError('Id do produto não encontrado ou inválido!');
        }

        $product = new EntityProduct();

        $product->id = $idProduto;

        $product = $product->getProductId();

        if (!$product instanceof EntityProduct) {
            return self::getPageError('Não existe produto cadastrado com esse código!');
        }

        return self::getProduct([
            'title' => 'Edição de produto',
            'msg' => '',
            'nome' => $product->nome,
            'descricao' => $product->descricao,
            'valor' => $product->valor
        ]);
    }
}
