<?php

namespace App\Controller\Pages;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use \App\Utils\View;
use \App\Model\Entity\User as EntityUser;

class User extends DefaultPage
{
    //Método responável por retornar a lista de usuarios
    public static function getTable()
    {
        //View da pagina home
        $content = View::render('pages/user/table', [
            'button-cadastro' => self::getBtnCadastro(),
            'content' => self::getRowsTable()
        ]);

        //Retorna a view da pagina
        return parent::getDefaultPage('Usuários', $content);
    }

    public static function getRowsTable()
    {
        $users = EntityUser::getUsers();

        $content = '';

        foreach ($users as $user) {
            $content .= View::render('pages/user/items/tr', [
                'id' => $user->id,
                'nome' => $user->nome,
                'cpf' => $user->cpf,
                'email' => $user->email,
                'cliente' => strtoupper($user->cliente) === 'S' ? 'Sim' : 'Não',
                'administrador' => strtoupper($user->administrador) === 'S' ? 'Sim' : 'Não'
            ]);
        }

        return $content;
    }

    //Método responável por retornar o formulario de usuarios
    public static function getForm($userData = [])
    {

        if (!isset($_SESSION['ADM'])) {
            return self::getPageError('Usuário não possui permissão!');
        }

        //View da pagina home
        $content = View::render('pages/user/form', [
            'title' => $userData['title'] ?? 'Cadastro de usuário',
            'msg-error-alert' => $userData['msg'] ?? '',
            'nome' => $userData['nome'] ?? '',
            'cpf' => $userData['cpf'] ?? '',
            'email' => $userData['email'] ?? '',
            'senha' => $userData['senha'] ?? '',
            'endereco' => $userData['endereco'] ?? '',
            'cliente' => $userData['cliente'] ?? '',
            'administrador' => $userData['administrador'] ?? ''
        ]);

        //Retorna a view da pagina
        return parent::getDefaultPage($userData['title'] ?? 'Cadastro de usuário', $content);
    }

    //Método responável por retornar o formulario de usuarios
    public static function saveUser($request, $idUsuario)
    {
        $postVars = $request->getPostVars();

        $user = new EntityUser();

        $user->id = $idUsuario;
        $user->nome = $postVars['nome'];
        $user->cpf = trim($postVars['cpf']);
        $user->email = trim($postVars['email']);
        $user->senha = $postVars['senha'];
        $user->endereco = $postVars['endereco'];
        $user->cliente = array_key_exists('cliente', $postVars) ? $postVars['cliente'] : '';
        $user->administrador = array_key_exists('administrador', $postVars) ? $postVars['administrador'] : '';
        $user->data_cadastro = date('Y-m-d H-i-s');

        $errors = $user->validaDados();

        if (strlen($errors)) {
            return self::getForm([
                'title' => 'Cadastro de usuário',
                'nome' => $user->nome,
                'cpf' => $user->cpf,
                'email' => $user->email,
                'senha' => $user->senha,
                'endereco' => $user->endereco,
                'cliente' => $user->cliente,
                'administrador' => $user->administrador,
                'msg' => $errors
            ]);
        }

        //Criptografando a senha
        $user->senha = password_hash($user->senha, PASSWORD_DEFAULT);

        if ($idUsuario) {
            if ($user->atualizar()) {
                return self::getForm([
                    'msg' => $user->getMsgFormat('Usuário editado com sucesso!', 'alert-success')
                ]);
            }
        } else {
            if ($user->cadastrar()) {
                return self::getForm([
                    'msg' => $user->getMsgFormat('Usuário cadastrado com sucesso!', 'alert-success')
                ]);
            }
        }
    }

    public static function getBtnCadastro()
    {
        return View::render('pages/user/items/btncadastro');
    }

    public static function editUser($idUsuario)
    {
        if (!isset($_SESSION['ADM'])) {
            return self::getPageError('Usuário não possui permissão!');
        }

        if (!isset($idUsuario) || !is_numeric($idUsuario)) {
            return self::getPageError('Id do usuário não encontrado ou inválido!');
        }

        $user = new EntityUser();

        $user->id = $idUsuario;

        $result =  $user->getUserId();

        if (!$result instanceof EntityUser) {
            return self::getPageError('Não existe usuário cadastrado com esse código!');
        }

        return self::getForm([
            'title' => 'Editar usuário',
            'nome' => $result->nome,
            'cpf' => $result->cpf,
            'email' => $result->email,
            'senha' => '',
            'endereco' => $result->endereco,
            'cliente' => $result->cliente,
            'administrador' => $result->administrador,
            'msg' => ''
        ]);
    }

    public static function getPageError($text)
    {
        $content = View::render('pages/error', [
            'content' => $text
        ]);

        return parent::getDefaultPage('Error', $content);
    }

    public static function removeUser($request, $idUsuario)
    {
        if (!isset($_SESSION['ADM'])) {
            return self::getPageError('Usuário não possui permissão!');
        }

        if (!isset($idUsuario) || !is_numeric($idUsuario)) {
            return self::getPageError('Id do usuário não encontrado ou inválido!');
        }

        $user = new EntityUser();

        $user->id = $idUsuario;

        $result =  $user->getUserId();

        if (!$result instanceof EntityUser) {
            return self::getPageError('Não existe usuário cadastrado com esse código!');
        }

        if ($request->getHttpMethod() === 'GET') {
            $content = View::render('pages/question', [
                'content' => 'Deseja remover o usuário ' . $result->nome . '?',
                'name' => 'Excluir',
                'icon' => 'fa-trash-alt'
            ]);

            return parent::getDefaultPage('Atenção', $content);
        }

        if ($request->getHttpMethod() === 'POST') {
            if ($user->excluir()) {
                $content = View::render('pages/success', [
                    'content' => 'Usuário removido com sucesso!'
                ]);

                return parent::getDefaultPage('Atenção', $content);
            }
        }
    }
}
