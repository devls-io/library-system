<?php 

namespace Leonardo\LibrarySystem\Controllers;
use Leonardo\LibrarySystem\Models\Entities\Loan;
use Leonardo\LibrarySystem\Models\Repositories\LoanRepository;
use function Leonardo\LibrarySystem\helpers\sendJson;

// Usando outros repositórios

use Leonardo\LibrarySystem\Models\Repositories\BookRepository;
use Leonardo\LibrarySystem\Models\Repositories\UserRepository;

class LoanController{
    private LoanRepository $loanRepo;
    private BookRepository $bookRepo;
    private UserRepository $userRepo;

    public function __construct(LoanRepository $loanRepo, BookRepository $bookRepo, UserRepository $userRepo){
        $this->loanRepo = $loanRepo;
        $this->bookRepo = $bookRepo;
        $this->userRepo = $userRepo;
    }

    public function index():void{
        try{
            $loans = $this->loanRepo->listarTodos();

            $listaPublica = [];

            foreach($loans as $loan){
                $listaPublica[] = [
                    "userId" => $loan->getUserId(),
                    "bookId" => $loan->getBookId(),
                    "id" => $loan->getId(),
                    "dataEmprestimo" => $loan->getDataEmprestimo(),
                    "dataDevolucao" => $loan->getDataDevolucao()
                ] ;
            }

            sendJson($listaPublica, 200);

        }catch (\Throwable $e) {
            sendJson(['sucesso'=>false,'erro'=>$e->getMessage()], 500);
        }
    }

    public function store():void{
        try{
            $jsonBruto = file_get_contents("php://input");
        $dados = json_decode($jsonBruto, true);

        $userId =  (int)($dados['userId'] ?? 0);
        $bookId = (int)($dados['bookId'] ?? 0);

        if (!$userId || !$bookId) {
            sendJson(['sucesso' => false, 'erro' => 'userId e bookId são obrigatórios.'], 400);
            return;
        }

        $usuario = $this->userRepo->buscarPorId($userId);
        if(!$usuario){
            sendJson(['sucesso'=>false,'erro'=>'Usuário não encontrado'], 404);
            return;
        }

        $livro = $this->bookRepo->buscarPorId($bookId);
        if(!$livro){
            sendJson(['sucesso'=>false,'erro'=>'Livro não encontrado'], 404);
            return;
        }

        // Verificar se o livro esta disponivel

        if(!$livro->getDisponivel()){
            sendJson(['sucesso'=>false,'erro'=>"Ops! Este livro já está emprestado"], 400);
            return;
        }

        // DataEmprestimo vem da classe
        // DataDevolucao é nula
        // Id será criado pelo próprio banco.
        $novoEmprestimo = new Loan($userId, $bookId);

        // checar se o emprestimo ocorreu
        $salvou = $this->loanRepo->salvar($novoEmprestimo);

        // Se o emprestimo foi concluido, o livro fica indisponivel
        if($salvou){
            $livro->setDisponivel(false);
            $this->bookRepo->atualizar($livro);
        }

        sendJson(['sucesso'=>true,'mensagem'=>"Empréstimo realizado! Boa leitura!"], 201);
        }catch (\Throwable $e) {
            sendJson(['sucesso' => false, 'erro' => $e->getMessage()], 500);
        }

        
    }

    public function show():void{
        try{
            $jsonBruto = file_get_contents("php://input");
            $dados = json_decode($jsonBruto,true);

            $id = (int)($dados['id'] ?? 0);

            $emprestimoObj = $this->loanRepo->buscarPorId($id);

            if($emprestimoObj){
                $emprestimoPublico = [
                    "userId"=>$emprestimoObj->getUserId(),
                    "bookId"=>$emprestimoObj->getBookId(),
                    "id"=> $emprestimoObj->getId(),
                    "dataEmprestimo"=>$emprestimoObj->getDataEmprestimo(),
                    "dataDevolucao"=>$emprestimoObj->getDataDevolucao()
                    
                ] ;
                sendJson($emprestimoPublico, 200);
            }else{
                sendJson(['sucesso'=>false,'erro'=>"Emprestimo não encontrado"], 404);
                
            }

        }catch (\Throwable $e) {
            sendJson(["sucesso"=>false, "erro"=> $e->getMessage()],500);
        }
    }

    public function update():void{
        try{
            $jsonBruto = file_get_contents("php://input");
            $dados = json_decode($jsonBruto,true);

            $id = (int)($dados['id'] ?? 0);

            $emprestimoExistente = $this->loanRepo->buscarPorId($id);

            if(!$emprestimoExistente){
                sendJson(['sucesso'=>false,'erro'=>'Emprestimo não encontrado'], 404);
                return;
            }

            if($emprestimoExistente->getDataDevolucao() !== null){
                sendJson(['sucesso' => false, 'erro' => 'Este empréstimo já foi devolvido'], 400);
                return;
            }

            $emprestimoExistente->marcarComoDevolvido();

            $atualizou = $this->loanRepo->updateDevolucao($emprestimoExistente);

            if($atualizou){
                $livro = $this->bookRepo->buscarPorId($emprestimoExistente->getBookId());
                if($livro){
                    $livro->setDisponivel(true);
                    $this->bookRepo->atualizar($livro);
                }
            }

            sendJson(['sucesso' => true, 'mensagem' => 'Livro devolvido com sucesso! Obrigado!'], 200);

        }catch (\Throwable $e) {
           sendJson(["sucesso"=>false, "erro"=> $e->getMessage()],500);
        }
    }
}


?>