<?php

namespace App\Model\Entity;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use \App\db\Database;

//Classe de ususario
class PedidoProduto
{
    public $id;
    public $id_pedido;
    public $id_produto;
    public $id_usuario;


    //Responsável por cadastrar o pedidoProduto.
    public function cadastrar()
    {
        $database = new Database('pedido_produto');

        $this->id = $database->insert([
            'id_pedido' => $this->id_pedido,
            'id_produto' => $this->id_produto,
            'id_usuario' => $this->id_usuario
        ]);

        return true;
    }
}
