<?php 

namespace Leonardo\LibrarySystem\Models\Entities;

use Exception;

class Book{
    private ?int $id;
    private string $titulo;
    private string $autor;
    private int $ano;
    private string $genero;
    private int $classificacao;
    private bool $disponivel;
    private int $totalPaginas;

    // ====== GETTERS:  ID ======

    public function getId(): ?int{
        return $this->id;
    }

    // ====== GETTERS & SETTERS: TITULO ======

    public function getTitulo():string{
        return $this->titulo;
    }


    public function setTitulo(string $titulo):void{
        if(empty(trim($titulo))){
            throw new Exception("O titulo não pode estar vazio");
        }
        $this->titulo = $titulo;
    }

     // ====== GETTERS & SETTERS: AUTOR ======

     public function getAutor():string{
        return $this->autor;
    }

    public function setAutor(string $autor):void{
        if(empty(trim($autor))){
            throw new Exception("O livro precisa ter um autor");
        }
        $this->autor = $autor;
    }

    // ====== GETTERS & SETTERS: ANO ======


    public function getAno():int{
        return $this->ano;
    }

    public function setAno(int $ano):void{
        $anoAtual = (int) date('Y');

        if($ano <= 0){
            throw new Exception("O ano não pode ser menor que 0");
        }

        if($ano > $anoAtual){
            throw new Exception("O ano do livro não pode ser no futuro!");
        } 

        $this->ano = $ano;
    }

    // ====== GETTERS & SETTERS: GENERO ======

    public function getGenero():string{
        return $this->genero;
    }

    public function setGenero(string $genero):void{
        if(empty(trim($genero))){
            throw new Exception("O livro deve ter um gênero");
        }
        $this->genero = $genero;
    }

    // ====== GETTERS & SETTERS: CLASSIFICACAO ======

    public function getClassificacao():int{
        return $this->classificacao;
    }

    public function setClassificacao(int $classificacao):void{
        $classificacoesPermitidas = [0,10,12,14,16,18];

        // true para comparação estrita
        if(!in_array($classificacao, $classificacoesPermitidas, true)){
            throw new Exception("Classificação inválida! Escolha entre os números: 0 (livre) , 10 , 12, 14 , 16 ou 18");
        }

        $this->classificacao = $classificacao;
    }

    // ====== GETTERS & SETTERS: DISPONIVEL ======

    public function getDisponivel():bool{
        return $this->disponivel;
    }

    public function setDisponivel(bool $disponivel):void{
        $this->disponivel = $disponivel;
    }
    
    // ====== GETTERS & SETTERS: TOTAL PAGINAS ======

    public function getTotalPaginas():int{
        return $this->totalPaginas;
    }

    public function setTotalPaginas(int $totalPaginas):void{
        if($totalPaginas <= 0){
            throw new Exception("O total de páginas deve ser maior que zero");
        }

        if($totalPaginas > 10000){
            throw new Exception("O limite máximo de páginas é 10.000");
        }

        $this->totalPaginas = $totalPaginas;
    }

    // ====== Construtor ======
    

    public function __construct(string $titulo, string $autor, int $ano, string $genero, int $classificacao, int $totalPaginas, ?int $id = null, bool $disponivel = true){
        $this->id = $id;
        $this->setTitulo($titulo);
        $this->setAutor($autor);
        $this->setAno($ano);
        $this->setGenero($genero);
        $this->setClassificacao($classificacao);
        $this->setDisponivel($disponivel); // começa disponivel
        $this->setTotalPaginas($totalPaginas);
    }



}


?>