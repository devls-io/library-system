# 📚 Library System API

Uma API RESTful para gerenciamento de biblioteca, desenvolvida em PHP puro, utilizando a arquitetura MVC (Model-View-Controller) e conceitos modernos de Orientação a Objetos.

## 🚀 Status do Projeto: Módulo de Usuários Concluído

O ecossistema base da aplicação e o CRUD completo de usuários foram implementados com sucesso e estão 100% funcionais.

---

## 🛠️ Arquitetura e Decisões Técnicas

Para fugir do PHP estruturado tradicional e simular o comportamento dos grandes frameworks de mercado (como Laravel e Symfony), o projeto foi estruturado com as seguintes camadas:

- **Roteador Dinâmico (`Router.php`):** Um sistema de rotas centralizado que intercepta as requisições globais (`$_SERVER`), limpa as URLs com `parse_url` e gerencia as rotas através de chaves bidimensionais (`$routes[$method][$path]`).
- **Closure Routes (`routes.php`):** Mapeamento moderno utilizando funções anônimas e a palavra-chave `use` para isolamento e injeção de dependência dos Controllers.
- **Camada de Repositório (`UserRepository.php`):** Responsável por isolar totalmente as consultas SQL (PDO) da lógica de negócios da aplicação.
- **Encapsulamento e Entidades (`User.php`):** Uso de Getters/Setters e tratamento seguro de dados (como criptografia de senha nativa e uso de `trim()`).
- **Tratamento de Erros:** Implementação de blocos `try/catch` nos Controllers, capturando exceções do banco (como e-mails duplicados) ou payloads inválidos e devolvendo respostas padronizadas em JSON com status HTTP corretos (`400 Bad Request`, `404 Not Found`).

---

## 🛣️ Endpoints Disponíveis (Módulo de Usuários)

Todas as requisições e respostas trafegam em formato **JSON**.

| Método   | Endpoint           | Descrição                               | Payload (JSON)                                |
| :------- | :----------------- | :-------------------------------------- | :-------------------------------------------- |
| **GET**  | `/usuarios`        | Lista todos os usuários cadastrados     | Nenhum                                        |
| **POST** | `/usuarios/store`  | Cadastra um novo usuário no sistema     | `{"nome", "idade", "sexo", "email", "senha"}` |
| **POST** | `/usuarios/show`   | Busca detalhes de um usuário específico | `{"id"}`                                      |
| **POST** | `/usuarios/update` | Atualiza dados de um usuário existente  | `{"id", "nome", ...}`                         |
| **POST** | `/usuarios/delete` | Remove um usuário do banco de dados     | `{"id"}`                                      |

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

   Crie um banco de dados com o nome `library_system` e execute o seguinte script SQL para criar a tabela de usuários:

   ```sql
    CREATE TABLE users (
      id INT AUTO_INCREMENT PRIMARY KEY,
      nome VARCHAR(255) NOT NULL,
      idade INT NOT NULL,
      sexo VARCHAR(50) NOT NULL,
      email VARCHAR(255) NOT NULL UNIQUE,
      senha VARCHAR(255) NOT NULL
   );
   ```

5. **Testando o projeto**

   Inicie o servidor embutido do PHP na raiz do projeto:

   ```bash
   php -S localhost:8000
   ```
