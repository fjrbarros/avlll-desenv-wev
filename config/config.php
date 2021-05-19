<?php

require __DIR__ . '/../vendor/autoload.php';

use \App\Utils\View;
use \WilliamCosta\DotEnv\Environment;

//Carrega variaveis de ambiente
Environment::load(__DIR__ . '/../');

//Define a url constante do projeto 
define('URL', getenv('URL'));

//Defini valor padrao das variaveis
View::init(['URL' => URL]);
