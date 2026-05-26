<?php 

namespace Leonardo\LibrarySystem\Models\Repositories;

use PDO; // nativo do PHP
use Leonardo\LibrarySystem\Models\Entities\User;

class UserRepository{
    // guarda a conexão com o banco
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Busca todos os usuários do banco de dados e os transforma em objetos User.
     * @return User[] Um array contendo objetos da classe User.
     */
    public function listarTodos():array{
        $sql = "SELECT * FROM users";

        $stmt = $this->db->query($sql);

        $linhas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // array para guardar os objetos;
        $usuarios = [];

        // cada linha retornada vira uma Entidade User

        foreach($linhas as $linha){
            $usuarios[] = new User($linha['nome'], (int)$linha['idade'],$linha['sexo'],$linha['email'],$linha['senha'],(int)$linha['id']);
        }

        return $usuarios;
    }

    /**
     * Pega uma Entity User cheia de superpoderes, desmonta ela e grava no MySQL.
     * @param User $usuario Objeto da entidade usuário contendo os dados validados.
     * @return bool Retorna true se salvou com sucesso.
     */
    public function salvar(User $usuario): bool{
        $sql = "INSERT INTO users (nome,idade,sexo,email,senha) VALUES (:nome, :idade, :sexo, :email, :senha)";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':nome', $usuario->getNome());
        $stmt->bindValue(':idade', $usuario->getIdade(), PDO::PARAM_INT);
        $stmt->bindValue(':sexo', $usuario->getSexo());
        $stmt->bindValue(':email', $usuario->getEmail());
        $stmt->bindValue(':senha', $usuario->getSenha());

        return $stmt->execute();
    }

    /**
     * Busca um usuário único no banco de dados baseado no id correspondente.
     * @param int $id ID do usuário.
     * @return User|null Retorna o objeto User ou null se não for encontrado.
     */

    public function buscarPorid(int $id): ?User{
        $sql = "SELECT * FROM users where id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $linha = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$linha){
            return null;
        }

        return new User(
            $linha['nome'],
            (int)$linha['idade'],
            $linha['sexo'],
            $linha['email'],
            $linha['senha'],
            (int)$linha['id']
        );

        
    }

    /**
     * Deleta um usuário do banco de dados baseado no ID.
     * @param int $id ID do usuário a ser removido.
     * @return bool Retorna true se a exclusão foi executada com sucesso.
     * 
     */
    public function deletar(int $id):bool{
        $sql = "DELETE FROM users WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();
        // true se a quantidade de linhas apagadas for maior que 0!
        return $stmt->rowCount() > 0;
    }

    /**
     * Atualiza os dados de um usuário existente no banco de dados.
     * @param User $usuario Objeto contendo os dados atualizados (e o ID preenchido!).
     * @return bool Retorna true se o usuário foi atualizado com sucesso.
    */

    public function atualizar(User $usuario):bool{
        $sql = "UPDATE users SET nome = :nome, idade = :idade, sexo = :sexo, email = :email, senha = :senha WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':nome', $usuario->getNome());
        $stmt->bindValue(':idade', $usuario->getIdade(), PDO::PARAM_INT);
        $stmt->bindValue(':sexo', $usuario->getSexo());
        $stmt->bindValue(':email', $usuario->getEmail());
        $stmt->bindValue(':senha', $usuario->getSenha());

        // Preencher com o Id

        $stmt->bindValue(':id', $usuario->getId(), PDO::PARAM_INT);

        $stmt->execute();

        // True ou False
        return $stmt->rowCount() > 0;
    }


}



?>