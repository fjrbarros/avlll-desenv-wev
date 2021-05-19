<?php

namespace App\Model\Entity;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
    $_SESSION['ADM'] = 'S';
}

use \App\db\Database;
use \PDO;

//Classe de ususario
class User
{
    public $id;
    public $nome;
    public $cpf;
    public $email;
    public $senha;
    public $endereco;
    public $cliente;
    public $administrador;
    public $data_cadastro;

    public function validaDados()
    {
        $errors = '';
        $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';

        if (!strlen(trim($this->nome))) {
            $errors .= 'Nome de usuário obrigatório! <br>';
        }

        if (!strlen(trim($this->cpf)) || strlen(trim($this->cpf)) != 11) {
            $errors .= 'Cpf inválido! <br>';
        }

        if (!strlen(trim($this->email)) || !preg_match($regex, $this->email)) {
            $errors .= 'E-mail inválido! <br>';
        }

        if (!strlen(trim($this->senha)) || strlen(trim($this->senha)) < 6) {
            $errors .= 'Senha deve possuir 6 ou mais caracteres! <br>';
        }

        if (empty(trim($this->cliente)) && empty(trim($this->administrador))) {
            $errors .= 'Necessário informar um tipo de usuário! <br>';
        }

        if (!$this->id) {
            if ($this->existeUsuarioCpf()) {
                $errors .= 'Já existe um usuário com esse cpf! <br>';
            }

            if ($this->existeUsuarioEmail()) {
                $errors .= 'Já existe um usuário com esse email! <br>';
            }
        }

        $errors = $this->getMsgFormat($errors, 'alert-danger');

        return $errors;
    }

    public function getMsgFormat($text, $class)
    {
        return strlen($text) ? '<div class="alert ' . $class . '"> ' . $text . '</div>' : '';
    }

    //Responsável por cadastrar o usuario.
    public function cadastrar()
    {
        $database = new Database('user');

        $this->id = $database->insert([
            'nome' => $this->nome,
            'cpf' => $this->cpf,
            'email' => $this->email,
            'senha' => $this->senha,
            'endereco' => $this->endereco,
            'cliente' => $this->cliente,
            'administrador' => $this->administrador,
            'data_cadastro' => $this->data_cadastro
        ]);

        return true;
    }

    //Responsável por atualizar o usuario.
    public function atualizar()
    {
        return (new Database('user'))->update('id =' . $this->id, [
            'nome' => $this->nome,
            'cpf' => $this->cpf,
            'email' => $this->email,
            'senha' => $this->senha,
            'endereco' => $this->endereco,
            'cliente' => $this->cliente,
            'administrador' => $this->administrador,
            'data_cadastro' => $this->data_cadastro
        ]);
    }

    //Busca todos usuarios do banco
    public static function getUsers($where = null, $order = null, $limit = null)
    {
        return (new Database('user'))->select($where, $order, $limit)->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    //Valida se o usuario é administrador
    public static function isAdm()
    {
        return array_key_exists('ADM', $_SESSION) ? strtoupper($_SESSION['ADM']) === 'S' : false;
    }

    //Valida se ja existe um usuário com o mesmo cpf
    public function existeUsuarioCpf()
    {
        $content = (new Database('user'))
            ->select('cpf = ' . "'$this->cpf'")
            ->fetchAll(PDO::FETCH_CLASS, self::class);

        return count($content) > 0;
    }


    //Valida se ja existe um usuário com o mesmo e-mail
    public function existeUsuarioEmail()
    {
        $content = (new Database('user'))
            ->select('email = ' . "'$this->email'")
            ->fetchAll(PDO::FETCH_CLASS, self::class);

        return count($content) > 0;
    }

    //Responsável por buscar um usuário pelo ID
    public function getUserId()
    {
        return (new Database('user'))->select('id = ' . $this->id)->fetchObject(self::class);
    }

     //Responsável por excluir um usuário.
     public function excluir()
     {
         return (new Database('user'))->delete('id = ' . $this->id);
     }
}
