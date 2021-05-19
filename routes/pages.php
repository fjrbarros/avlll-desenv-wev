<?php

use \App\Http\Response;
use \App\Controller\Pages;


//Rota Home
$router->get('/', [
    function () {
        return new Response(200, Pages\Home::getHome());
    }
]);

//Rota listagem de usuarios
$router->get('/user-list', [
    function () {
        return new Response(200, Pages\User::getTable());
    }
]);

//Rota cadastro de usuarios
$router->get('/user-register', [
    function () {
        return new Response(200, Pages\User::getForm());
    }
]);

//Rota cadastro de usuarios
$router->post('/user-register', [
    function ($request) {
        return new Response(200, Pages\User::saveUser($request));
    }
]);

//Rota edição de usuarios
$router->get('/user-edit/{idUsuario}', [
    function ($idUsuario) {
        return new Response(200, Pages\User::editUser($idUsuario));
    }
]);