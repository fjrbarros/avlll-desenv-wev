<?php

namespace App\Model\Entity;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use \App\db\Database;
use \PDO;

class Product {
    public $id;
    public $nome;
    public $arquivo;
    public $descricao;
    public $valor;
    public $data_cadastro;
    public $id_user;

    //Valida dados obrigatorios do produto
    public function validaDados() {
        $errors = '';

        if (!strlen(trim($this->nome))) {
            $errors .= 'Nome do produto obrigatório! <br>';
        }

        if (!strlen(trim($this->descricao))) {
            $errors .= 'Descrição do produto obrigatório! <br>';
        }

        if (!strlen(trim($this->valor))) {
            $errors .= 'Valor do produto obrigatório! <br>';
        }

        $errors = $this->getMsgFormat($errors, 'alert-danger');

        return $errors;
    }

    //Formata a mensagem e estiliza com css
    public function getMsgFormat($text, $class)
    {
        return strlen($text) ? '<div class="alert ' . $class . '"> ' . $text . '</div>' : '';
    }

    public function cadastrar()
    {
        $database = new Database('product');

        $this->id = $database->insert([
            'nome' => $this->nome,
            'descricao' => $this->descricao,
            'valor' => $this->valor,
            'id_user' => 2
        ]);

        return true;
    }

    //Busca todos produtos do banco
    public function getProducts($where = null, $order = null, $limit = null)
    {
        return (new Database('product'))->select($where, $order, $limit)->fetchAll(PDO::FETCH_CLASS, self::class);
    }
}