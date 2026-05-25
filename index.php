<?php 

// (Autoload + .env)
require_once __DIR__ . '/bootstrap.php';

use Leonardo\LibrarySystem\Database\Connection;

try{
    $pdo = Connection::getConnection();

    echo "<h1>Sucesso! O PHP se conectou ao Mysql usando OOP e caminho Lógico! </h1>";
} catch(Exception $e){
    echo "<p>Ih, deu ruim: " . $e->getMessage();
}

?>