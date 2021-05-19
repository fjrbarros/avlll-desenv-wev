<?php

namespace App\Http;

use \Closure;
use \Exception;
use \ReflectionFunction;

class Router
{

    //Url completa do projeto
    private $url = '';

    //Prefixo de todas rotas
    private $prefix = '';

    //Indice de rotas
    private $routes = [];

    //Instancia de request
    private $request;

    //Construtor da classe
    public function __construct($url)
    {
        $this->request = new Request();
        $this->url = $url;
        $this->setPrefix();
    }

    //Metodo responsavel por definir o prefixo das rotas
    public function setPrefix()
    {
        $parseUrl = parse_url($this->url);

        //Define o prefixo
        $this->prefix = $parseUrl['path'] ?? '';
    }

    //Metodo responsavel por adicionar uma rota na classe
    public function addRoute($method, $route, $params = [])
    {
        //Validação dos parametros
        foreach ($params as $key => $value) {
            if ($value instanceof Closure) {
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        //Variavei da rota
        $params['variables'] = [];

        //Padrão de validação das variaveis das rotas
        $patternVariable = '/{(.*?)}/';

        if (preg_match_all($patternVariable, $route, $matches)) {
            $route = preg_replace($patternVariable, '(.*?)', $route);
            $params['variables'] = $matches[1];
        }

        //Padrão de validaçao da url
        $patternRoute = '/^' . str_replace('/', '\/', $route) . '$/';

        //Adiciona a rota dentro da clase
        $this->routes[$patternRoute][$method] = $params;
    }

    //Metodo responsavel por definir uma rota de GET
    public function get($route, $params = [])
    {
        return $this->addRoute('GET', $route, $params);
    }

    //Metodo responsavel por definir uma rota de POST
    public function post($route, $params = [])
    {
        return $this->addRoute('POST', $route, $params);
    }

    //Metodo responsavel por definir uma rota de PUT
    public function put($route, $params = [])
    {
        return $this->addRoute('PUT', $route, $params);
    }

    //Metodo responsavel por definir uma rota de DELETE
    public function delete($route, $params = [])
    {
        return $this->addRoute('DELETE', $route, $params);
    }

    //Metodo responsavel por retornar a uri desconsiderando o prefixo
    public function getUri()
    {
        //Uri da request
        $uri = $this->request->getUri();

        //Fatia a uri com prefixo
        $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

        //Retorna uri sem prefixo
        return end($xUri);
    }

    //Metodo responsavel por retornar os dados da rota atual
    private function getRoute()
    {
        //Uri
        $uri = $this->getUri();

        //Method
        $httpMethod = $this->request->getHttpMethod();

        //Valida as rotas
        foreach ($this->routes as $patternRoute => $methods) {

            //Verifica se a uri bate com o padrão
            if (preg_match($patternRoute, $uri, $matches)) {
                //Verifica o metodo
                if (isset($methods[$httpMethod])) {
                    //Remove a primeira posição
                    unset($matches[0]);

                    //Variaveis processadas
                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
                    $methods[$httpMethod]['variables']['request'] = $this->request;

                    //Retorna os parametros da rota
                    return $methods[$httpMethod];
                }

                throw new Exception('Método não permitido', 405);
            }
        }

        //Url não encontrada
        throw new Exception('URL não encontrada', 404);
    }

    //Metodo responsavel por executar a rota atual
    public function run()
    {
        try {
            //Obtem a rota atual
            $route = $this->getRoute();

            //Verifica o controlador
            if (!isset($route['controller'])) {
                throw new Exception('URL não pode ser processada', 500);
            }

            //Argumentos da função
            $args = [];

            //Reflection
            $reflection = new ReflectionFunction($route['controller']);

            foreach ($reflection->getParameters() as $parameter) {
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }

            //Retorna a execução da função
            return call_user_func_array($route['controller'], $args);
        } catch (Exception $e) {
            return new Response($e->getCode(), $e->getMessage());
        }
    }
}
