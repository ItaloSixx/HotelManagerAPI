# Sistema de Gerenciamento Hoteleiro

## 📋 Sobre o Projeto

Este projeto é um sistema  de gerenciamento hoteleiro que oferece um conjunto de módulos para facilitar a gestão de estabelecimentos hoteleiros, 
É mportante ressaltar que o sistema foi desenvolvido com base no que eu visualizei após modelar o banco de dados, tentei atender todas as funcionalidades 
que o modelo do banco deixou explicito, portanto há várias funcionalidades não solicitas no desafio. 

## 🚀 Tecnologias Utilizadas

- PHP 8.1+
- Laravel Framework
- MySQL
- Docker
- Swagger/OpenAPI 3.0
- Laravel Sanctum

## 💡 Funcionalidades por Nível de Acesso

### Admin
- Acesso completo ao sistema
- Gerenciamento de hotéis
- Gerenciamento de usuários
- Todas as funcionalidades do Recepcionista

### Recepcionista
- Gerenciamento de cupons e promoções
- Gerenciamento completo de reservas
- Gerenciamento de diárias
- Cadastro e gestão de hóspedes
- Gerenciamento de pagamentos
- Gerenciamento de quartos
- Associação de hóspedes às reservas

## 🗄️ Modelo do Banco de Dados



### Estrutura Principal


O sistema possui rotas REST para todas as entidades do banco de dados, permitindo operações CRUD em:
- Usuários
- Quartos 
- Reservas
- Pagamentos
- Clientes
- Cupons e Juros
- Autenticação

## 📚 Documentação da API

A documentação completa da API está disponível através do Swagger/OpenAPI 3.0. Para acessá-la, após iniciar o servidor, visite:

```bash
http://seu-caminho/public/api/documentation
```

## 🔒 Segurança

- Autenticação via Laravel Sanctum
- Validação de dados em todas as requisições
- Middleware de autorização por função

## 📦 Pré-requisitos

- Docker e Docker Compose
- Git

## ⚙️ Instalação e Configuração

1. Clone o repositório:
```bash
git clone https://github.com/seu-usuario/nome-do-repo.git
cd nome-do-repo
```

2. Construa a imagem Docker:
```bash
docker build -t php_app /caminho/da/Imagen/api_hotel
```

3. Inicie os containers:
```bash
docker-compose up -d
```

4. Acesse o container e execute os seguintes comandos:
```bash
composer install
php artisan migrate
php artisan import:xml
```

5. Para parar os containers:
```bash
docker-compose down
```

> **Nota**: O arquivo docker-compose.yml já contém todas as variáveis de ambiente necessárias configuradas para facilitar o processo de instalação.

## 🔐 Sistema de Autenticação e Autorização

Durante o desenvolvimento deste projeto, notei um comportamento inesperado com as rotas. Após realizar testes em diferentes máquinas, descobri que era necessário incluir "/public" antes de todas as rotas para que elas funcionassem corretamente. Por exemplo:

Antes: /api/login
Agora: /public/api/login
Portanto, se a forma convencional (sem o /public) não funcionar, por favor, adicione "/public" ao início das rotas.

#Configuração do Ambiente
Download do Arquivo de Rotas: Você pode baixar o arquivo de rotas clicando aqui, execute via postman ou insomnia.
#Configuração das Variáveis de Ambiente:
Atualize as variáveis de ambiente para refletir o caminho e a porta corretos do seu container Docker.
#Cadastro de Usuário:
Crie um usuário com papel de admin ou receptionist.
#Login e Token:
Realize o login, copie o token de autenticação gerado e inclua-o nas variáveis de ambiente.

### Rotas Públicas
```bash
POST /api/login           # Autenticação de usuários
POST /api/users          # Registro de novos usuários
```

### Rotas Autenticadas (Admin e Recepcionista)
```bash
# Gerenciamento de Cupons
GET     /api/coupons
POST    /api/coupons
GET     /api/coupons/{id}
PUT     /api/coupons/{id}
DELETE  /api/coupons/{id}

# Gerenciamento de Reservas
GET     /api/reserves
POST    /api/reserves
GET     /api/reserves/{id}
PUT     /api/reserves/{id}
DELETE  /api/reserves/{id}

# Gerenciamento de Diárias
GET     /api/dailies
POST    /api/dailies
GET     /api/dailies/{id}
PUT     /api/dailies/{id}
DELETE  /api/dailies/{id}

# Gerenciamento de Hóspedes
GET     /api/guests
POST    /api/guests
GET     /api/guests/{id}
PUT     /api/guests/{id}
DELETE  /api/guests/{id}

# Gerenciamento de Pagamentos
GET     /api/payments
POST    /api/payments
GET     /api/payments/{id}
PUT     /api/payments/{id}
DELETE  /api/payments/{id}

# Gerenciamento de Quartos
GET     /api/rooms
POST    /api/rooms
GET     /api/rooms/{id}
PUT     /api/rooms/{id}
DELETE  /api/rooms/{id}

# Relacionamento Reservas-Hóspedes
POST    /api/reserves/{reserveId}/addGuest
GET     /api/reserves/{reserveId}/getGuest
GET     /api/reserves/{guestId}/getReserve
DELETE  /api/reserves/{reserveId}/{guestId}/rmvGuest
```

### Rotas Exclusivas para Admin
```bash
# Gerenciamento de Hotéis
GET     /api/hotels
POST    /api/hotels
GET     /api/hotels/{id}
PUT     /api/hotels/{id}
DELETE  /api/hotels/{id}

# Gerenciamento de Usuários
GET     /api/users
GET     /api/users/{id}
PUT     /api/users/update/{id}
PATCH   /api/users/update/{id}
DELETE  /api/users/{id}
```

### Rota de Logout
```bash
POST    /api/logout      # Requer autenticação
```



## 📝 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.
