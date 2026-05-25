<?php 

// Endereço lógico
namespace Leonardo\LibrarySystem\Database;

use PDO;
use PDOException;

class Connection{
    // Guardar a conexão na memoria
    // começa null, static permite que a classe lembre
    private static ?PDO $instance = null;

    public static function getConnection(): PDO{
        if(self::$instance === null){
            try{
                // Pegando as senhas que o bootstrap jogou na memória ($_ENV)
                $host = $_ENV['DB_HOST'];
                $port = $_ENV['DB_PORT'];
                $db = $_ENV['DB_DATABASE'];
                $user = $_ENV['DB_USER'];
                $pass = $_ENV['DB_PASS'];

                $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

                self::$instance = new PDO($dsn, $user, $pass);
            
                // Ativa os erros para estourar no try/catch se algo der errado
               self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
               
            }  catch (PDOException $e) {
                die("Erro ao conectar ao banco de dados: " . $e->getMessage());
            }


        }
        // Caso o if não rode, é porque já temos uma conexão.
        return self::$instance;
    }
}


?>