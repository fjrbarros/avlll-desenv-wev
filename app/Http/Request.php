<?php

namespace App\Http;

class Request
{
    //Metodo http da requisição
    private $httpMethod;

    //Uri da pagina
    private $uri;

    //Parametro que vem na url ($_GET)
    private $queryParams = [];

    //Parametro recebidos no post da pagina ($_POST)
    private $postVars = [];

    //Cabeçalho da requisição
    private $headers = [];

    public function __construct()
    {
        $this->queryParams = $_GET ?? [];
        $this->postVars = $_POST ?? [];
        $this->headers = getallheaders();
        $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->uri = $_SERVER['REQUEST_URI'] ?? '';
    }

    //Método responsavel por retornar o metodo http da requisição
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    //Método responsavel por retornar o Uri da requisição
    public function getUri()
    {
        return $this->uri;
    }

    //Método responsavel por retornar os headers da requisição
    public function getHeaders()
    {
        return $this->headers;
    }

    //Método responsavel por retornar os parametros da url da requisição
    public function getQueryParams()
    {
        return $this->queryParams;
    }

    //Método responsavel por retornar as variaveis post da requisição
    public function getPostVars()
    {
        return $this->postVars;
    }
}
