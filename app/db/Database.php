<?php

namespace App\db;

use \PDO;
use \PDOException;

//Classe de mapeamento do banco.
class Database
{
    //Configurações padrões da conexão.
    const host = 'localhost';
    const name = 'avlll_desenvweb';
    const user = 'root';
    const pass = 'barros123';

    private $table;
    private $connection;

    //Construtor da classe
    public function __construct($table = null)
    {
        $this->table = $table;
        $this->setConnection();
    }

    //Função responsável por efeturar e tratar a conexão.
    private function setConnection()
    {
        try {
            $this->connection = new PDO('mysql:host=' . self::host . ';dbname=' . self::name, self::user, self::pass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    //Função responsável por executar a query passada por parametro.
    public function execute($query, $params = [])
    {
        try {
            $statement = $this->connection->prepare($query);
            $statement->execute($params);
            return $statement;
        } catch (PDOException $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    //Função responsável pelo insert do novo registro.
    public function insert($values)
    {
        $fields = array_keys($values);
        $binds = array_pad([], count($fields), '?');

        $query = 'insert into ' . $this->table . ' (' . implode(',', $fields) . ') values (' . implode(',', $binds) . ')';

        $this->execute($query, array_values($values));

        return $this->connection->lastInsertId();
    }

    //Função responsável por recuperar os dados do banco.
    public function select($where = null, $order = null, $limit = null, $fields = '*')
    {
        $where = strlen($where) ? 'where ' . $where : '';
        $order = strlen($order) ? 'order by ' . $order : '';
        $limit = strlen($limit) ? 'limit ' . $limit : '';

        $query = 'select ' . $fields . ' from ' . $this->table . ' ' . $where . ' ' . $order . ' ' . $limit;
        
        return $this->execute($query);
    }

    //Função responsável por atualizar um novo registro.
    public function update($where, $values)
    {
        $fields = array_keys($values);

        $query = 'update ' . $this->table . ' set ' . implode('=?,', $fields) . '=? where ' . $where;

        $this->execute($query, array_values($values));
        return true;
    }

    //Função responsável por remover um registro do banco.
    public function delete($where)
    {
        $query = 'delete from ' . $this->table . ' where ' . $where;

        $this->execute($query);
        return true;
    }
}
