<?php 
namespace Leonardo\LibrarySystem\Models\Repositories;

use PDO;
use Leonardo\LibrarySystem\Models\Entities\Loan;

class LoanRepository{
    private PDO $db;

     public function __construct(PDO $db){
        $this->db = $db;
    }

    /**
     * Busca todos os emprestimos do banco de dados e transforma em objetos Loan.
     * @return Loan[] Um array contento objetos da classe Loan
     */

    public function listarTodos():array{
        $sql = "SELECT * FROM loans";
        $stmt = $this->db->query($sql);

        $linhas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $emprestimos = [];

        foreach($linhas as $linha){
            $emprestimos[] = new Loan($linha['user_id'], $linha['book_id'], $linha['id'], $linha['data_emprestimo'], $linha['data_devolucao']);
        }

        return $emprestimos;
    }

    /**
     * Salvar no mysql
     * @param Loan $loan Objeto da entidade 
     * @return bool Retorna true se salvou com sucesso
     */

    public function salvar(Loan $loan):bool{
        $sql = "INSERT INTO loans (user_id,book_id, data_emprestimo, data_devolucao) VALUES (:user_id,:book_id, :data_emprestimo, :data_devolucao)";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':user_id', $loan->getUserId(), PDO::PARAM_INT);
        $stmt->bindValue(':book_id', $loan->getBookId(), PDO::PARAM_INT);
        $stmt->bindValue(':data_emprestimo', $loan->getDataEmprestimo(), PDO::PARAM_STR);
        $stmt->bindValue(':data_devolucao', $loan->getDataDevolucao(), PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Busca um emprestimo único no banco de dados baseado no id correspondente.
     * @param int $id ID do emprestimo.
     * @return Loan|null Retorna o objeto Loan ou null se não for encontrado.
     */

    public function buscarPorId(int $id): ?Loan{
        $sql = "SELECT * FROM loans where id = :id";
        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $linha = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$linha){
            return null;
        }

        return new Loan(
            $linha['user_id'],
            $linha['book_id'],
            $linha['id'],
            $linha['data_emprestimo'],
            $linha['data_devolucao']
        );
    }

    /**
     * Atualiza apenas a data de devolução de um empréstimo existente.
     * @param Loan $loan Objeto do empréstimo com a data de devolução atualizada.
     * @return bool Retorna true em caso de sucesso ou false em caso de erro.
     */

    public function updateDevolucao(Loan $loan):bool{
        $sql = "UPDATE loans SET data_devolucao = :data_devolucao WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':data_devolucao', $loan->getDataDevolucao(), PDO::PARAM_STR);
        $stmt->bindValue(':id', $loan->getId(), PDO::PARAM_INT);

        $stmt->execute();

        // True ou False
        return $stmt->rowCount() > 0;
    }
    

}


?>