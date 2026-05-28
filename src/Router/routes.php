<?php 

// Arquivo de definição de rotas

use Leonardo\LibrarySystem\Models\Repositories\UserRepository;
use Leonardo\LibrarySystem\Controllers\UserController;

/**
 * @var \PDO $pdo 
 * @var \Leonardo\LibrarySystem\Router\Router $router 
 */

// Instanciar o controller

$userRepo = new UserRepository($pdo); // vem do index.php
$userController = new UserController($userRepo);

// 2. Registramos as rotas dizendo exatamente o que cada uma deve acionar
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


?>