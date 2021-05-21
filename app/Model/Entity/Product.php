<?php

namespace App\Model\Entity;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use \App\db\Database;
use \PDO;

class Product
{
    public $id;
    public $nome;
    public $arquivo;
    public $descricao;
    public $valor;
    public $data_cadastro;
    public $id_user;

    //Valida dados obrigatorios do produto
    public function validaDados()
    {
        $msg = ['error' => '', 'nameFile' => ''];

        if (!strlen(trim($this->nome))) {
            $msg['error'] .= 'Nome do produto obrigatório! <br>';
        }

        if (!strlen(trim($this->descricao))) {
            $msg['error'] .= 'Descrição do produto obrigatório! <br>';
        }

        if (!strlen(trim($this->valor))) {
            $msg['error'] .= 'Valor do produto obrigatório! <br>';
        }

        if (!strlen($msg['error'])) {
            $validFile = $this->validaArquivoInserido();
            $msg['error'] .= $validFile['error'];
            $msg['nameFile'] = $validFile['nameFile'];
        }

        $msg['error'] = $this->getMsgFormat($msg['error'], 'alert-danger');

        return $msg;
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
            'arquivo' => $this->arquivo,
            'descricao' => $this->descricao,
            'valor' => $this->valor,
            'id_user' => $this->id_user
        ]);

        return true;
    }

    //Busca todos produtos do banco
    public function getProducts($where = null, $order = null, $limit = null)
    {
        return (new Database('product'))->select($where, $order, $limit)->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    //Valida arquivo para upload
    public function validaArquivoInserido()
    {
        $arrayData = ['error' => '', 'nameFile' => ''];

        if (empty($_FILES['file']['name'])) {
            return $arrayData;
        }

        if (($_FILES['file']['size'] / 1048576) > 5) {
            $arrayData = ['error' => 'Tamanho de arquivo excedido, tamanho máximo de 5MB!', 'nameFile' => ''];
            return $arrayData;
        }

        $formatosPermitidos = array('png', 'jpeg', 'jpg');
        $extensao = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

        $pastaFiles = __DIR__ . '/../../Files/';

        if ($_FILES['file']['error'] != 0) {
            $arrayData = ['error' => 'Erro no upload do arquivo, verifique o tipo e tamanho do arquivo!', 'nameFile' => ''];
            return $arrayData;
        }

        if (!in_array($extensao, $formatosPermitidos)) {
            $arrayData = ['error' => 'Formato de arquivo não suportado!', 'nameFile' => ''];
            return $arrayData;
        }

        $tmpName = $_FILES['file']['tmp_name'];
        $newName = uniqid() . '.' . $extensao;

        if (!move_uploaded_file($tmpName, $pastaFiles . $newName)) {
            $arrayData = ['error' => 'Erro ao fazer upload do arquivo!', 'nameFile' => ''];
        }

        $arrayData = ['error' => '', 'nameFile' => $newName];
        return $arrayData;
    }

    //Responsável por buscar um usuário pelo ID
    public function getProductId()
    {
        return (new Database('product'))->select('id = ' . $this->id)->fetchObject(self::class);
    }

    //Responsável por excluir um produto.
    public function excluir()
    {
        return (new Database('product'))->delete('id =' . $this->id);
    }

    //Responsável por atualizar o produto.
    public function atualizar()
    {
        return (new Database('product'))->update('id =' . $this->id, [
            'nome' => $this->nome,
            'arquivo' => $this->arquivo,
            'descricao' => $this->descricao,
            'valor' => $this->valor,
            'id_user' => $this->id_user
        ]);
    }
}
