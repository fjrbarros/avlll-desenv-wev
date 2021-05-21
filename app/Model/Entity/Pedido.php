<?php

namespace App\Model\Entity;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use \App\db\Database;

//Classe de ususario
class Pedido
{
    public $id;
    public $valor_total;
    public $data;

    //Responsável por cadastrar o pedido.
    public function cadastrar()
    {
        $database = new Database('pedido');

        $this->id = $database->insert(['valor_total' => $this->valor_total]);

        return true;
    }
}
