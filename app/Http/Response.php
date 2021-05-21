<?php

namespace App\Http;

class Response
{
    //Codigo do status HTTP
    private $httpCode = 200;

    //Cabeçalho do response
    private $headers = [];

    //Tipo de conteudo retornado na requisição
    private $contentType = 'text/html';

    //Conteudo do response
    private $content;

    //Construtor da classe
    public function __construct($httpCode, $content, $contentType = 'text/html')
    {
        $this->httpCode = $httpCode;
        $this->content = $content;
        $this->setContentType($contentType);
    }

    //Responsavel por alterar o content type do response
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        $this->addHeader('Content-Type', $contentType);
    }

    public function addHeader($key, $value)
    {
        $this->headers[$key] = $value;
    }

    //Metodo responsavel por enviar os headers para o navegador
    private function sendHeaders()
    {
        //Status
        http_response_code($this->httpCode);

        //Enviar headers
        foreach ($this->headers as $key => $value) {
            header($key . ': ' . $value);
        }
    }

    //Metodo responsavel por enviar a resposta para o usuario
    public function sendResponse()
    {
        //Envia os headers
        $this->sendHeaders();

        //Imprimi o conteudo
        switch ($this->contentType) {
            case 'text/html':
                echo $this->content;
                exit;
        }
    }
}
