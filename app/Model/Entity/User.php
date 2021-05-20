<?php

namespace App\Model\Entity;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
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
    public $ativo;

    //Valida os dados obrigatorios para cadastro ou edição
    public function validaDados()
    {
        $errors = '';

        if (!strlen(trim($this->nome))) {
            $errors .= 'Nome de usuário obrigatório! <br>';
        }

        if (!strlen(trim($this->cpf)) || strlen(trim($this->cpf)) != 11) {
            $errors .= 'Cpf inválido! <br>';
        }

        $errors .= $this->validaEmail();

        $errors .= $this->validaSenha();

        if (empty(trim($this->cliente)) && empty(trim($this->administrador))) {
            $errors .= 'Necessário informar um tipo de usuário! <br>';
        }

        if (!$this->id) {
            if ($this->getUserCpf() instanceof $this > 0) {
                $errors .= 'Já existe um usuário com esse cpf! <br>';
            }

            if ($this->getUserEmail() instanceof $this > 0) {
                $errors .= 'Já existe um usuário com esse email! <br>';
            }
        }

        $errors = $this->getMsgFormat($errors, 'alert-danger');

        return $errors;
    }

    //Valida o email informado
    public function validaEmail()
    {
        $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';

        if (!strlen(trim($this->email)) || !preg_match($regex, $this->email)) {
            return 'E-mail inválido! <br>';
        }

        return '';
    }

    //Valida a senha informada
    public function validaSenha()
    {
        if (!strlen(trim($this->senha)) || strlen(trim($this->senha)) < 6) {
            return 'Senha deve possuir 6 ou mais caracteres! <br>';
        }

        return '';
    }

    //Valida os dados do login
    public function validaDadosLogin()
    {
        $errors = '';

        $errors .= $this->validaEmail();
        $errors .= $this->validaSenha();

        $userDb = $this->getUserEmail();

        if (!strlen($errors)) {
            if (!$userDb instanceof $this) {
                $errors .= 'Usuário não cadastrado!';
            } else {
                //Verifica se a senha informada é a mesma salva no banco
                if (!password_verify($this->senha, $userDb->senha)) {
                    $errors .= 'Senha inválida!';
                }
            }
        }

        $errors = $this->getMsgFormat($errors, 'alert-danger');

        //Se o login deu certo, cria uma sessão com alguns dados
        if (!strlen($errors)) {
            $_SESSION['ADM'] = strtoupper($userDb->administrador) === 'S';
            $_SESSION['USER_ID'] = $userDb->id;
            $_SESSION['lOGADO'] = true;
        }

        return $errors;
    }

    //Formata a mensagem e estiliza com css
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
            'data_cadastro' => $this->data_cadastro,
            'ativo' => $this->ativo
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
        return isset($_SESSION['ADM']);
    }

    //Responsável por buscar o usuario pelo cpf
    public function getUserCpf()
    {
        $content = (new Database('user'))->select('cpf = ' . "'$this->cpf'")->fetchObject(self::class);

        return $content;
    }

    //Responsável por buscar um usuário pelo ID
    public function getUserId()
    {
        return (new Database('user'))->select('id = ' . $this->id)->fetchObject(self::class);
    }

    //Responsável por buscar um usuário pelo Email
    public function getUserEmail()
    {
        return (new Database('user'))->select('email = ' . "'$this->email'")->fetchObject(self::class);
    }

    //Responsável por excluir um usuário.
    public function excluir()
    {
        return (new Database('user'))->update('id =' . $this->id, ['ativo' => 'N']);
    }
}
