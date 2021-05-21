<?php

use \App\Http\Response;
use \App\Controller\Pages;


//Rota Home
$router->get('/', [
    function () {
        return new Response(200, Pages\Home::getHome());
    }
]);

//Rota Home
$router->post('/', [
    function ($request) {
        return new Response(200, Pages\Home::addProduct($request));
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
        return new Response(200, Pages\User::saveUser($request, null));
    }
]);

//Rota edição de usuarios
$router->get('/user-edit/{idUsuario}', [
    function ($idUsuario) {
        return new Response(200, Pages\User::editUser($idUsuario));
    }
]);

//Rota edição de usuarios
$router->post('/user-edit/{idUsuario}', [
    function ($request, $idUsuario) {
        return new Response(200, Pages\User::saveUser($request, $idUsuario));
    }
]);

//Rota valida remoção de usuarios
$router->get('/user-remove/{idUsuario}', [
    function ($request, $idUsuario) {
        return new Response(200, Pages\User::removeUser($request, $idUsuario));
    }
]);


//Rota remoção de usuarios
$router->post('/user-remove/{idUsuario}', [
    function ($request, $idUsuario) {
        return new Response(200, Pages\User::removeUser($request, $idUsuario));
    }
]);

//Rota pagina de login
$router->get('/login', [
    function () {
        return new Response(200, Pages\Login::getLogin());
    }
]);

//Rota para fazer o login
$router->post('/login', [
    function ($request) {
        return new Response(200, Pages\Login::fazerLogin($request));
    }
]);

//Rota pgina de logout
$router->get('/logout', [
    function () {
        return new Response(200, Pages\Login::getLogout());
    }
]);

//Rota para fazer o logout
$router->post('/logout', [
    function () {
        return new Response(200, Pages\Login::fazerLogout());
    }
]);

//Rota para formulario de produto
$router->get('/product', [
    function () {
        return new Response(200, Pages\Product::getProduct());
    }
]);

//Rota para cadastro de produto
$router->post('/product', [
    function ($request) {
        return new Response(200, Pages\Product::saveProduct($request, null));
    }
]);

//Rota para remover produto
$router->get('/product-delete/{idProduto}', [
    function ($request, $idProduto) {
        return new Response(200, Pages\Product::removeProduct($request, $idProduto));
    }
]);

// Rota para remover produto
$router->post('/product-delete/{idProduto}', [
    function ($request, $idProduto) {
        return new Response(200, Pages\Product::removeProduct($request, $idProduto));
    }
]);

//Rota edição de produto
$router->get('/product-edit/{idProduto}', [
    function ($request, $idProduto) {
        return new Response(200, Pages\Product::editProduct($request, $idProduto));
    }
]);

//Rota edição de produto
$router->post('/product-edit/{idProduto}', [
    function ($request, $idProduto) {
        return new Response(200, Pages\Product::saveProduct($request, $idProduto));
    }
]);

//Rota para o carrinho
$router->get('/cart', [
    function () {
        return new Response(200, Pages\Cart::getCart());
    }
]);

//Rota para remover produto do carrinho
$router->get('/cart-remove/{idProdutoSession}', [
    function ($request, $idProdutoSession) {
        return new Response(200, Pages\Cart::removeItemCart($request, $idProdutoSession));
    }
]);

//Rota para remover produto do carrinho
$router->post('/cart-remove/{idProdutoSession}', [
    function ($request, $idProdutoSession) {
        return new Response(200, Pages\Cart::removeItemCart($request, $idProdutoSession));
    }
]);