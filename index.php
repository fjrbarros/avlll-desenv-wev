<?php

require __DIR__ . '/config/config.php';

use \App\Http\Router;

$router = new Router(URL);

include __DIR__ . '/routes/pages.php';

$router->run()->sendResponse();

// echo "<pre>";
// print_r($query);
// echo "</pre>";
// exit;