<?php 

namespace Leonardo\LibrarySystem\Router;

use function Leonardo\LibrarySystem\helpers\sendJson;

class Router{
    private array $routes = [];

    /**
     * Cadastra uma rota no nosso armário
     * @param string $method GET ou POST
     * @param string $path A URL (ex: '/usuarios')
     * @param callable $callback A função anônima que vai rodar
     */

    public function add(string $method, string $path, callable $callback){
        $this->routes[$method][$path] = $callback;
    }

    public function run(){
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/' , PHP_URL_PATH);

        // Se existir o metodo e a url rodamos a função
        if(isset($this->routes[$method][$uri])){
            $this->routes[$method][$uri]();
        } else{
            sendJson(['sucesso' => false, 'erro' => "Rota nao encontrada: {$method} {$uri}"], 404);
        }
    }
}


?>