<?php 

namespace Leonardo\LibrarySystem\Controllers;

use Leonardo\LibrarySystem\Models\Entities\Book;
use Leonardo\LibrarySystem\Models\Repositories\BookRepository;
use function Leonardo\LibrarySystem\helpers\sendJson;

class BookController{
    private BookRepository $bookRepo;

    public function __construct(BookRepository $bookRepo){
        $this->bookRepo = $bookRepo;
    }

    public function index():void{
       try{
        $livros = $this->bookRepo->listarTodos();

        // array limpo que vai virar JSON
        $listaPublica = [];

        foreach($livros as $livro){
            $listaPublica[] = [
                "id" => $livro->getId(),
                "titulo" => $livro->getTitulo(),
                "autor"=> $livro->getAutor(),
                "ano" => $livro->getAno(),
                "genero" => $livro->getGenero(),
                "classificacao" => $livro->getClassificacao(),
                "totalPaginas" => $livro->getTotalPaginas(),
                "disponivel" => $livro->getDisponivel()
            ];
        }

        sendJson($listaPublica, 200);

       } catch (\Throwable $e) {
         sendJson(["sucesso"=>false, "erro"=> $e->getMessage()], 500);
       }
    }

    public function store():void{
        try{
            $jsonBruto = file_get_contents("php://input");
            $dados = json_decode($jsonBruto, true);

            $newBook = new Book(
                $dados['titulo'],
                $dados['autor'],
                $dados['ano'],
                $dados['genero'],
                $dados['classificacao'],
                $dados['totalPaginas']
            );

            $this->bookRepo->salvar($newBook);

            sendJson(["sucesso"=>true,"mensagem"=>"Registro inserido com sucesso!"], 201);

        } catch (\Throwable $e) {
            sendJson(["sucesso"=>false,"erro"=>$e->getMessage()], 400);
        }
    }

    public function show():void{
        try{
         $jsonBruto = file_get_contents('php://input');
         $dados = json_decode($jsonBruto, true);
         //  Proteção contra o ID vir vazio
         $id = (int)($dados['id'] ?? 0);

         $livroObj = $this->bookRepo->buscarPorId($id);

         if($livroObj){
            $livroPublico = [
                "id" => $livroObj->getId(),
                "titulo" => $livroObj->getTitulo(),
                "autor"=> $livroObj->getAutor(),
                "ano" => $livroObj->getAno(),
                "genero" => $livroObj->getGenero(),
                "classificacao" => $livroObj->getClassificacao(),
                "totalPaginas" => $livroObj->getTotalPaginas(),
                "disponivel" => $livroObj->getDisponivel()
            ];
            sendJson($livroPublico, 200);
            
         } else{
            sendJson(["sucesso"=>false, "erro"=>"Livro não encontrado"], 404);
         }

        } catch (\Throwable $e) {
           sendJson(["sucesso"=>false, "erro"=> $e->getMessage()],500);
        }
    }

    public function update():void{
        try{
            $jsonBruto = file_get_contents("php://input");
            $dados = json_decode($jsonBruto, true);

            $id = (int)($dados['id'] ?? 0 );

            $livroExistente = $this->bookRepo->buscarPorId($id);

            if($livroExistente){
                if(!empty($dados['titulo'])){
                    $livroExistente->setTitulo($dados['titulo']);
                }
                if(!empty($dados['autor'])){
                    $livroExistente->setAutor($dados['autor']);
                }
                if(!empty($dados['ano'])){
                    $livroExistente->setAno((int)$dados['ano']);
                }
                if(!empty($dados['genero'])){
                    $livroExistente->setGenero($dados['genero']);
                }

                if(isset($dados['classificacao']) && $dados['classificacao'] !== ''){
                    $livroExistente->setClassificacao((int)$dados['classificacao']);
                }

                if(!empty($dados['totalPaginas'])){
                    $livroExistente->setTotalPaginas((int)$dados['totalPaginas']);
                }

                $this->bookRepo->atualizar($livroExistente);
                sendJson(['sucesso'=>true,"mensagem"=>"registro atualizado com sucesso"], 200);
            } else{
                sendJson(["sucesso"=>false, "erro"=>"Livro não encontrado"], 404);
            }

        } catch (\Throwable $e) {
            sendJson(["sucesso"=>false,"erro"=>$e->getMessage()], 500);
        }
    }


    public function destroy():void{
        try{
            $jsonBruto = file_get_contents("php://input");
            $dados = json_decode($jsonBruto, true);

            $id = (int)($dados['id'] ?? 0 );

            $deletar = $this->bookRepo->deletar($id);

            if($deletar){
                sendJson(["sucesso"=>true ,"mensagem"=>"Livro deletado com sucesso"], 200);
            } else{
                sendJson(["sucesso"=>false, "erro"=>"Não foi possivel deletar. Livro não encontrado"], 404);
            }

        } catch (\Throwable $e) {
            sendJson(['sucesso'=>false, 'erro'=>$e->getMessage()], 500);
        }
    }


}

?>