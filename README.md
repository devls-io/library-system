# 📚 Library System API

Uma API RESTful para gerenciamento de biblioteca, desenvolvida em PHP puro, utilizando a arquitetura MVC (Model-View-Controller) e conceitos modernos de Orientação a Objetos.

## 🚀 Status do Projeto: Módulos de Usuários e Livros Concluídos

O ecossistema base da aplicação, o CRUD completo de usuários e livros foram implementados com sucesso e estão 100% funcionais, incluindo validações de regras de negócio.

---

## 🛠️ Arquitetura e Decisões Técnicas

Para fugir do PHP estruturado tradicional e simular o comportamento dos grandes frameworks de mercado (como Laravel e Symfony), o projeto foi estruturado com as seguintes camadas:

- **Roteador Dinâmico (`Router.php`):** Um sistema de rotas centralizado que intercepta as requisições globais (`$_SERVER`), limpa as URLs com `parse_url` e gerencia as rotas através de chaves bidimensionais (`$routes[$method][$path]`).

- **Closure Routes (`routes.php`):** Mapeamento moderno utilizando funções anônimas e a palavra-chave `use` para isolamento e injeção de dependência dos Controllers.

- **Camada de Repositório (`UserRepository.php`, `BookRepository.php`):** Responsável por isolar totalmente as consultas SQL (PDO) da lógica de negócios e persistência da aplicação.

- **Encapsulamento e Entidades (`User.php`, `Book.php`):** Uso rigoroso de Getters/Setters para validação de regras de negócio na porta de entrada (como criptografia de senhas, whitelists de classificação indicativa e travas de datas futuras).

- **Tratamento de Erros e Controllers:** Implementação de blocos `try/catch` centralizados nos Controllers, capturando exceções de validação ou do banco de dados e devolvendo respostas padronizadas em JSON com status HTTP corretos (`400 Bad Request`, `404 Not Found`, `500 Internal Error`).

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
   ```

5. **Testando o projeto**

   Inicie o servidor embutido do PHP na raiz do projeto:

   ```bash
   php -S localhost:8000
   ```

   Agora a aplicação está pronta para receber requisições! Você pode importar as rotas no Postman utilizando o endereço http://localhost:8000 seguido dos endpoints documentados acima.
