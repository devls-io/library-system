<?php 

namespace Leonardo\LibrarySystem\Models\Repositories;

use PDO;
use Leonardo\LibrarySystem\Models\Entities\Book;

class BookRepository{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Busca todos os livros do banco de dados e transforma em objetos Book.
     * @return Book[] Um array contento objetos da classe Book.
     */
    public function listarTodos():array{
        $sql = "SELECT * FROM books";

        $stmt = $this->db->query($sql);

        $linhas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // array para os objetos
        $livros = [];

        // cada linha vira uma entidade Book

        foreach ($linhas as $linha) {
        $livros[] = new Book(
        $linha['titulo'], 
        $linha['autor'], 
        (int) $linha['ano'], 
        $linha['genero'], 
        (int) $linha['classificacao'],
        (int) $linha['totalPaginas'], 
        (int) $linha['id'],
        (bool) $linha['disponivel']
    );
}

        return $livros;
    }

    /**
     * Salvar no mysql
     * @param Book $livro Objeto da entidade 
     * @return bool Retorna true se salvou com sucesso
     */

    public function salvar(Book $livro):bool{
        $sql = "INSERT INTO books (titulo,autor,ano,genero,classificacao,disponivel,totalPaginas) VALUES (:titulo, :autor, :ano, :genero, :classificacao, :disponivel, :totalPaginas)";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':titulo', $livro->getTitulo());
        $stmt->bindValue(':autor', $livro->getAutor());
        $stmt->bindValue(':ano', $livro->getAno(), PDO::PARAM_INT);
        $stmt->bindValue(':genero', $livro->getGenero());
        $stmt->bindValue(':classificacao', $livro->getClassificacao(), PDO::PARAM_INT);
        $stmt->bindValue(':disponivel', (int)$livro->getDisponivel(), PDO::PARAM_INT);
        $stmt->bindValue(':totalPaginas', $livro->getTotalPaginas(), PDO::PARAM_INT);

        return $stmt->execute();

    }

    /**
     * Buscar um livro unico no banco de dados baseado no id
     * @param int $id ID do livro.
     * @return Book|null Retorna o objeto Book ou null
     */

    public function buscarPorId(int $id): ?Book{
        $sql = "SELECT * FROM books WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $linha = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$linha){
            return null;
        }

        return new Book(
        $linha['titulo'],
        $linha['autor'], 
        (int)$linha['ano'], 
        $linha['genero'], 
        (int)$linha['classificacao'],
        (int)$linha['totalPaginas'], 
        (int)$linha['id'],
        (bool)$linha['disponivel']
        );
    }

    /**
     * Deleta um livro do banco de dados baseado no ID.
     * @param int $id ID do livro a ser removido.
     * @return bool Retorna true se a exclusão foi executada com sucesso
     */

    public function deletar(int $id):bool{
        $sql = "DELETE FROM books WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        // true se a quantidade de linhas apagadas for maior que 0!
        return $stmt->rowCount() > 0;

    }

    /**
     * Atualiza os dados de um livro existente no banco de dados
     * @param Book $livro Objeto contendo os dados atualizados
     * @return bool Retorna true se o livro foi atualizado com sucesso.
     */

    public function atualizar(Book $livro):bool{
        $sql = "UPDATE books SET titulo = :titulo, autor = :autor, ano = :ano, genero = :genero, classificacao = :classificacao, disponivel = :disponivel, totalPaginas = :totalPaginas WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':titulo', $livro->getTitulo());
        $stmt->bindValue(':autor', $livro->getAutor());
        $stmt->bindValue(':ano', $livro->getAno(), PDO::PARAM_INT);
        $stmt->bindValue(':genero', $livro->getGenero());
        $stmt->bindValue(':classificacao', $livro->getClassificacao(), PDO::PARAM_INT);
        $stmt->bindValue(':disponivel', (int)$livro->getDisponivel(), PDO::PARAM_INT);
        $stmt->bindValue(':totalPaginas', $livro->getTotalPaginas(), PDO::PARAM_INT);

        // Preencher com o Id

        $stmt->bindValue(':id', $livro->getId(), PDO::PARAM_INT);
        $stmt->execute();
        // True ou False
        return $stmt->rowCount() > 0;


    }

}

?>