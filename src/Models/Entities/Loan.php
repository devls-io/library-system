<?php 

namespace Leonardo\LibrarySystem\Models\Entities;

class Loan{
    private ?int $id; // null porque o ID vem do banco depois.
    private int $userId;
    private int $bookId;
    private string $dataEmprestimo;
    private ?string $dataDevolucao; // null porque não foi devolvido


    // ========== GETTERS ==========

    public function getId(): ?int{
        return $this->id;
    }

    public function getUserId(): int{
        return $this->userId;
    }

    public function getBookId(): int{
        return $this->bookId;
    }

    public function getDataEmprestimo(): string{
        return $this->dataEmprestimo;
    }

    public function getDataDevolucao(): ?string{
        return $this->dataDevolucao;
    }

    // ========== MÉTODOS DE NEGÓCIO (Substitui o Setter) ==========

    public function marcarComoDevolvido(): void{
        $this->dataDevolucao = date("Y-m-d H:i:s");
    }

    // ====== Construtor ======

    public function __construct(
        int $userId,
        int $bookId,
        ?int $id = null,
        ?string $dataEmprestimo = null,
        ?string $dataDevolucao = null
    ){
        $this->id = $id;
        $this->userId = $userId;
        $this->bookId = $bookId;

        // Se a data empréstimo não foi passada é emprestimo novo
        $this->dataEmprestimo = $dataEmprestimo ?? date("Y-m-d H:i:s");
        $this->dataDevolucao = $dataDevolucao;
    }

    

}

?>