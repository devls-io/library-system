<?php 

// Arquivo de definição de rotas

use Leonardo\LibrarySystem\Models\Repositories\UserRepository;
use Leonardo\LibrarySystem\Controllers\UserController;
use Leonardo\LibrarySystem\Models\Repositories\BookRepository;
use Leonardo\LibrarySystem\Controllers\BookController;
use Leonardo\LibrarySystem\Models\Repositories\LoanRepository;
use Leonardo\LibrarySystem\Controllers\LoanController;

/**
 * @var \PDO $pdo 
 * @var \Leonardo\LibrarySystem\Router\Router $router 
 */

$userRepo = new UserRepository($pdo); // vem do index.php
$userController = new UserController($userRepo);

//Registramos as rotas dizendo exatamente o que cada uma deve acionar

// ==========================================
// RECURSO: USUÁRIOS
// ==========================================
$router->add('GET', '/usuarios', function() use ($userController) {
    $userController->index();
});

$router->add('POST', '/usuarios/store', function() use ($userController) {
    $userController->store();
});

$router->add('POST', '/usuarios/show', function() use ($userController) {
    $userController->show();
});

$router->add('POST', '/usuarios/update', function() use ($userController) {
    $userController->update();
});

$router->add('POST', '/usuarios/delete', function() use ($userController) {
    $userController->destroy();
});

// ==========================================
// RECURSO: LIVROS
// ==========================================

$bookRepo = new BookRepository($pdo);
$bookController = new BookController($bookRepo);

$router->add('GET', '/livros', function() use ($bookController){
    $bookController->index();
});

$router->add('POST', '/livros/store', function() use ($bookController){
    $bookController->store();
});

$router->add('POST', '/livros/show', function() use ($bookController){
    $bookController->show();
});

$router->add('POST', '/livros/update', function() use ($bookController){
    $bookController->update();
});

$router->add('POST', '/livros/delete', function() use ($bookController){
    $bookController->destroy();
});

// ==========================================
// RECURSO: EMPRESTIMOS
// ==========================================

$loanRepo = new LoanRepository($pdo);
$loanController = new LoanController($loanRepo, $bookRepo, $userRepo);

$router->add('GET', '/emprestimos', function() use ($loanController){
    $loanController->index();
});

$router->add('POST', '/emprestimos/store', function() use ($loanController){
    $loanController->store();
});

$router->add('POST', '/emprestimos/show', function() use ($loanController){
    $loanController->show();
});

$router->add('POST', '/emprestimos/update', function() use ($loanController){
    $loanController->update();
});

?>