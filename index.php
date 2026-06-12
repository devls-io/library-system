<?php 
// Config de fuso-horario
date_default_timezone_set('America/Sao_Paulo');
// (Autoload + .env)
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/src/helpers/response.php';

use Leonardo\LibrarySystem\Database\Connection;
use Leonardo\LibrarySystem\Router\Router;
use function Leonardo\LibrarySystem\helpers\sendJson;

try{
    $pdo = Connection::getConnection();
    $router = new Router();

    //  Importa o arquivo de rotas (ele vai conseguir ler o $pdo e o $router aqui de cima!)
    require_once __DIR__ . '/src/Router/routes.php';

    $router->run();
} catch (\Throwable $e) {
    sendJson([
        'sucesso' => false, 
        'erro' => 'Erro critico no sistema: ' . $e->getMessage()
    ], 500);
}




?>