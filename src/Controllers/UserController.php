<?php 

namespace Leonardo\LibrarySystem\Controllers;

use Leonardo\LibrarySystem\Models\Entities\User;
use Leonardo\LibrarySystem\Models\Repositories\UserRepository;
use function Leonardo\LibrarySystem\helpers\sendJson;

class UserController{
    private UserRepository $userRepo;

    public function __construct(UserRepository $userRepo){
        $this->userRepo = $userRepo;
    }

    public function index(){
        try{
            // Busca a lista viva de objetos User
            $usuariosObj = $this->userRepo->listarTodos();

            // array limpo que vai virar JSON
            $listaPublica = [];

            // extraindo dados

            foreach($usuariosObj as $usuario){
                $listaPublica[] = [
                    'id'  => $usuario->getId(),
                    'nome'=> $usuario->getNome(),
                    'idade'=> $usuario->getIdade(),
                    'sexo'=> $usuario->getSexo(),
                    'email'=> $usuario->getEmail()
                    // A senha ficou de fora por segurança! 🛡️
                ];
            }

            sendJson($listaPublica, 200);

        } catch (\Throwable $e) {
            sendJson(['sucesso'=>false, 'erro'=>$e->getMessage()], 500);
        }
    }


    public function store(){
        try{
            $jsonBruto = file_get_contents('php://input');

            $dados = json_decode($jsonBruto, true);

            // Facilidade top! Não precisamos limpar os dados porque a classe já faz isso com o trim() graças aos setters

            $newUser = new User($dados['nome'], $dados['idade'], $dados['sexo'], $dados['email'], $dados['senha']);

           $newUser->criptografarSenha();

           
            $this->userRepo->salvar($newUser);
            sendJson([
                'sucesso'=>true,
                'mensagem'=> 'Registro inserido com sucesso!'
            ], 201);
            
           


        } catch (\Throwable $e) {
             sendJson(['sucesso'=>false, 'erro'=>$e->getMessage()], 400);
        }
    }


    public function show(){
        try{
            $jsonBruto = file_get_contents('php://input');
            $dados = json_decode($jsonBruto, true);
            //  Proteção contra o ID vir vazio
            $id = (int)($dados['id'] ?? 0);

            $usuarioObj = $this->userRepo->buscarPorId($id);

            if($usuarioObj){
                $usuarioPublico = [
                'id'  => $usuarioObj->getId(),
                'nome'=> $usuarioObj->getNome(),
                'idade'=> $usuarioObj->getIdade(),
                'sexo'=> $usuarioObj->getSexo(),
                'email'=> $usuarioObj->getEmail()
            ];
             sendJson($usuarioPublico, 200);
            } else{
                sendJson(['sucesso'=>false,'erro'=>'Usuário não encontrado'], 404);
            }
            

        } catch (\Throwable $e) {
            sendJson(['sucesso'=>false, 'erro'=>$e->getMessage()], 500);
        }


        
    }

    public function update(){
        try{
            $jsonBruto = file_get_contents('php://input');
            $dados = json_decode($jsonBruto, true);

            $id = (int)($dados['id'] ?? 0 );

            $usuarioExistente = $this->userRepo->buscarPorId($id);

            if($usuarioExistente){
                if(!empty($dados['nome'])){
                    $usuarioExistente->setNome($dados['nome']);
                }
                if(!empty($dados['idade'])){
                    $usuarioExistente->setIdade((int)$dados['idade']);
                }
                if(!empty($dados['sexo'])){
                    $usuarioExistente->setSexo($dados['sexo']);
                }
                if(!empty($dados['email'])){
                    $usuarioExistente->setEmail($dados['email']);
                }
                if(!empty($dados['senha'])){
                    $usuarioExistente->setSenha($dados['senha']);
                    $usuarioExistente->criptografarSenha();
                }

                $this->userRepo->atualizar($usuarioExistente);

                sendJson(['sucesso'=>true,"mensagem"=>"registro atualizado com sucesso"], 200);
            } else{
         sendJson(["sucesso"=>false, "erro"=>"Usuário não encontrado"], 404);
       }
        } catch (\Throwable $e) {
           sendJson(['sucesso'=>false, 'erro'=>$e->getMessage()], 500);
        }
    }


    public function destroy(){
        try{
            $jsonBruto = file_get_contents('php://input');
            $dados = json_decode($jsonBruto, true);
            //  Proteção contra o ID vir vazio
            $id = (int)($dados['id'] ?? 0);

            $deletar = $this->userRepo->deletar($id);

            if($deletar){
                sendJson(["sucesso"=>true ,"mensagem"=>"Usuário deletado com sucesso"], 200);
            } else{
                sendJson(["sucesso"=>false, "erro"=>"Não foi possivel deletar. Usuário não encontrado"], 404);
            }
        } catch (\Throwable $e) {
             sendJson(['sucesso'=>false, 'erro'=>$e->getMessage()], 500);
        }


    }

}

?>