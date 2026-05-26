<?php 

namespace Leonardo\LibrarySystem\Models\Entities;

use Exception;

// 1- atributos  2- Construtor 3 - Getters and Setters

class User{
    // Nulo inicialmente
    private ?int $id;
    private string $nome;
    private int $idade;
    private string $sexo;
    private string $email;
    private string $senha;

    // ====== GETTERS:  ID ======


    public function getId():int{
        return $this->id;
    }

    // ====== GETTERS & SETTERS: NOME ======

    public function getNome():string{
        return $this->nome;
    }

    public function setNome(string $nome):void{
        if(empty(trim($nome))){
            throw new Exception("O nome não pode estar vazio");
        }
        $this->nome = trim($nome);
    }

    // ====== GETTERS & SETTERS: IDADE ======

    public function getIdade():int{
        return $this->idade;
    }

    public function setIdade(int $idade):void{
        if($idade < 0 || $idade > 100){
            throw new Exception("A idade digitada é inválida");
        }

        $this->idade = $idade;
    }

    // ====== GETTERS & SETTERS: SEXO ======

    public function getSexo(): string{
        return $this->sexo;
    }

    public function setSexo(string $sexo):void{
        if (empty(trim($sexo))) {
            throw new Exception("O campo sexo não pode estar vazio.");
        }
        $this->sexo = trim($sexo);
    }

    // ====== GETTERS & SETTERS: E-MAIL ======

    public function getEmail():string{
        return $this->email;
    }

    public function setEmail(string $email):void{
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            throw new Exception("O e-mail digitado é inválido");
        }
        $this->email = trim($email);
    }

    // ====== GETTERS & SETTERS: SENHA ======

    public function getSenha():string{
        return $this->senha;
    }

    public function setSenha(string $senha):void{
        if(empty(trim($senha))){
            throw new Exception("A senha não pode estar vazia");
        }

        $this->senha = trim($senha);
    }

    // ====== MÉTODO ESTRATÉGICO: CRIPTOGRAFIA ======

    public function criptografarSenha():void{
        $this->senha = password_hash($this->senha, PASSWORD_DEFAULT);
    }

    // ====== Construtor ======

    public function __construct(string $nome, int $idade, string $sexo, string $email, string $senha, ?int $id = null){
        
        $this->id = $id;
        $this->setNome($nome);
        $this->setIdade($idade);
        $this->setSexo($sexo);
        $this->setEmail($email);
        $this->setSenha($senha);
    }
    
}


?>