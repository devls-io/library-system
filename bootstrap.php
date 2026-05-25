<?php 
// Ativar o Autoload do Composer
// o arquivo a seguir foi gerado pelo composer
require_once __DIR__ . "/vendor/autoload.php";

// Carregar o arquivo .env para a memória
// O pacote 'Dotenv' vai ler o arquivo .env

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

?>