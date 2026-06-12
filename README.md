# 📚 Library System API

Uma API RESTful para gerenciamento de biblioteca, desenvolvida em PHP puro, utilizando a arquitetura MVC (Model-View-Controller) e conceitos modernos de Orientação a Objetos.

## 🚀 Status do Projeto: Sistema Completo (Usuários, Livros e Empréstimos Concluídos)

O ecossistema base da aplicação, incluindo o CRUD completo de usuários e livros, além do módulo de fluxo de empréstimos e devoluções com sincronização de estados, foram implementados com sucesso e estão 100% funcionais.

## 🛠️ Arquitetura e Decisões Técnicas

Para fugir do PHP estruturado tradicional e simular o comportamento dos grandes frameworks de mercado (como Laravel e Symfony), o projeto foi estruturado com as seguintes camadas:

- **Roteador Dinâmico (`Router.php`):** Um sistema de rotas centralizado que intercepta as requisições globais (`$_SERVER`), limpa as URLs com `parse_url` e gerencia as rotas através de chaves bidimensionais (`$routes[$method][$path]`).

- **Closure Routes (`routes.php`):** Mapeamento moderno utilizando funções anônimas e a palavra-chave `use` para isolamento e injeção de dependência dos Controllers.

- **Camada de Repositório (`UserRepository.php`, `BookRepository.php`, `LoanRepository.php`):** Responsável por isolar totalmente as consultas SQL (PDO) da lógica de negócios e persistência da aplicação.

- **Encapsulamento e Entidades (`User.php`, `Book.php`, `Loan.php`):** Uso rigoroso de Getters/Setters para validação de regras de negócio na porta de entrada (como criptografia de senhas, whitelists de classificação indicativa, gerenciamento de fuso horário preciso com precisão de segundos e travas de consistência).

- **Sincronização de Estados e Cláusulas de Guarda:** O fluxo de empréstimo implementa travas lógicas rigorosas. Um livro só pode ser emprestado se estiver com o status `disponivel = true`. Caso contrário, a aplicação intercepta a requisição precocemente, protegendo a integridade do banco contra duplicidades desnecessárias. Na devolução, o estado do livro é restaurado de forma síncrona.

- **Tratamento de Erros e Controllers:** Implementação de blocos `try/catch` centralizados nos Controllers, capturando exceções de validação ou do banco de dados e devolvendo respostas padronizadas em JSON com status HTTP corretos (`200 OK`, `201 Created`, `400 Bad Request`, `404 Not Found`, `500 Internal Error`).

---

## 🛣️ Endpoints Disponíveis

Todas as requisições e respostas trafegam em formato **JSON**.

### 👤 Módulo de Usuários

| Método   | Endpoint           | Descrição                               | Payload (JSON)                                |
| :------- | :----------------- | :-------------------------------------- | :-------------------------------------------- |
| **GET**  | `/usuarios`        | Lista todos os usuários cadastrados     | Nenhum                                        |
| **POST** | `/usuarios/store`  | Cadastra um novo usuário no sistema     | `{"nome", "idade", "sexo", "email", "senha"}` |
| **POST** | `/usuarios/show`   | Busca detalhes de um usuário específico | `{"id"}`                                      |
| **POST** | `/usuarios/update` | Atualiza dados de um usuário existente  | `{"id", "nome", ...}`                         |
| **POST** | `/usuarios/delete` | Remove um usuário do banco de dados     | `{"id"}`                                      |

### 📚 Módulo de Livros

| Método   | Endpoint         | Descrição                             | Payload (JSON)                                                          |
| :------- | :--------------- | :------------------------------------ | :---------------------------------------------------------------------- |
| **GET**  | `/livros`        | Lista todos os livros cadastrados     | Nenhum                                                                  |
| **POST** | `/livros/store`  | Cadastra um novo livro no sistema     | `{"titulo", "autor", "ano", "genero", "classificacao", "totalPaginas"}` |
| **POST** | `/livros/show`   | Busca detalhes de um livro específico | `{"id"}`                                                                |
| **POST** | `/livros/update` | Atualiza dados de um livro existente  | `{"id", "titulo", "autor", "ano", "genero", ...}`                       |
| **POST** | `/livros/delete` | Remove um livro do banco de dados     | `{"id"}`                                                                |

### 🤝 Módulo de Empréstimos e Devoluções

| Método   | Endpoint              | Descrição                                                      | Payload (JSON)         |
| :------- | :-------------------- | :------------------------------------------------------------- | :--------------------- |
| **GET**  | `/emprestimos`        | Lista o histórico de todos os empréstimos registrados          | Nenhum                 |
| **POST** | `/emprestimos/store`  | Registra um novo empréstimo e altera o livro para indisponível | `{"userId", "bookId"}` |
| **POST** | `/emprestimos/update` | Realiza a devolução do livro e altera o status para disponível | `{"id"}`               |
| **POST** | `/emprestimos/show`   | Busca detalhes de um empréstimo específico                     | `{"id"}`               |

---

## 🧪 Como Testar

### 📋 Pré-requisitos

Antes de começar, você vai precisar ter instalado em sua máquina:

- **Um servidor local instalado (ex: **XAMPP**, **WAMP** ou **Laragon**)**
- **PHP 8.2** ou superior
- **Composer** (Gerenciador de dependências do PHP)
- **MySQL 8.0** ou superior

---

### ⚙️ Instalação e Configuração

1. **Clone o repositório:**

   Navegue até a pasta `htdocs` do seu servidor local e execute:

   ```bash
   git clone https://github.com/devls-io/library-system.git
   ```

2. **Instale as dependências do projeto**

   `composer install`

3. **Configure as variáveis de Ambiente:**

   Crie um arquivo na raiz chamado `.env`
   Adicione as credenciais do banco de dados:

   ```env
   DB_HOST=localhost
   DB_PORT=3306
   DB_NAME=library_system
   DB_USER=seu_usuario
   DB_PASS=sua_senha
   ```

4. **Configure o Banco de Dados**

   Crie um banco de dados com o nome `library_system` e execute o seguinte script SQL para criar as tabelas de usuários e livros:

   ```sql
   CREATE TABLE users(
      id INT AUTO_INCREMENT PRIMARY KEY,
      nome VARCHAR(255) NOT NULL,
      idade INT NOT NULL,
      sexo VARCHAR(50) NOT NULL,
      email VARCHAR(255) NOT NULL UNIQUE,
      senha VARCHAR(255) NOT NULL
   );

   CREATE TABLE books(
      id INT AUTO_INCREMENT PRIMARY KEY,
      titulo VARCHAR(100) NOT NULL,
      autor VARCHAR(100) NOT NULL,
      ano INT NOT NULL,
      genero VARCHAR(100) NOT NULL,
      classificacao INT NOT NULL,
      disponivel BOOLEAN DEFAULT TRUE,
      totalPaginas INT NOT NULL
   );

   CREATE TABLE loans(
      id INT AUTO_INCREMENT PRIMARY KEY,
      user_id INT NOT NULL,
      book_id INT NOT NULL,
      data_emprestimo TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
      data_devolucao TIMESTAMP NULL DEFAULT NULL,
      CONSTRAINT fk_emprestimo_usuario FOREIGN KEY (user_id) REFERENCES users(id),
      CONSTRAINT fk_emprestimo_livro FOREIGN KEY (book_id) REFERENCES books(id)

   );
   ```

5. **Testando o projeto**

   Inicie o servidor embutido do PHP na raiz do projeto:

   ```bash
   php -S localhost:8000
   ```

   Agora a aplicação está pronta para receber requisições! Você pode importar as rotas no Postman utilizando o endereço http://localhost:8000 seguido dos endpoints documentados acima.

---

## 🏁 Conclusão do MVP e Próximos Passos (Transição para v2)

Com a entrega do módulo de empréstimos, o objetivo principal desta **v1** foi **100% atingido**: consolidar os fundamentos do PHP Vanilla, aplicar de forma prática os conceitos de **Orientação a Objetos (OOP)** e entender os bastidores de um padrão arquitetural MVC real.

Como a base teórica e prática foi totalmente validada, esta versão foi oficialmente encerrada. O projeto agora evoluirá para a **`library-system-v2`**, onde utilizaremos o framework **Laravel** para dar tração ao ecossistema e implementar recursos avançados de mercado:

- **Interface Gráfica (Front-end):** Criação de uma interface para o usuário interagir com o sistema, saindo do Postman.
- **Autenticação e Níveis de Acesso (Roles):** Sistema de login seguro para diferenciar as ações de Administradores (bibliotecários) e Leitores (usuários).
- **Geração de Documentos:** Integração com envio de e-mails automáticos e geração de comprovantes de empréstimo em PDF.
