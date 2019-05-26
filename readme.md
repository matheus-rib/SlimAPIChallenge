# Slim API

EN_US: SLIM API for a Backend Dev Challenge
PT_BR: API SLIM para um desafio de Desenvolvimento em Backend
## Prerequisites | Pré-requisitos
### EN_US:
1. Create the ".env" file using ".env.example" as base
2. Create your Database (preferably MySQL) - Collation: utf8_unicode_ci
3. If your database is MySQL, execute the "create_*_table.sql" located in "scripts" folder
4. For test GET routes with pre-made examples: Fill database (in "scripts" folder has some fill_*.sql files)
5. Run the API using the command in terminal in project's root folder: php -S localhost:8080 -t public public/index.php
### PT_BR:
1. Criar o arquivo ".env" usando o ".env.example" como base
2. Criar a base de dados (preferencialmente MySQL) - Collation: utf8_unicode_ci
3. Se o banco for MySQL, executar as "create_*_table.sql" na pasta "scripts"
4. Para testar as rotas GET com exemplos pré-prontos: Preencher a base de dados (na pasta "scripts" possui alguns arquivos fill_*.sql)
5. Rode a API usando o comando no terminal no root da pasta do projeto: php -S localhost:8080 -t public public/index.php
## Example JSON's and Routes
### POST: /v1/products
EN_US: Insert a new Product in "products" table
PT_BR: Insere um novo produto na tabela "products"
{
"sku": 8552515751438644,
"name": "Casaco Jaqueta Outletdri Inverno Jacquard",
"price": 109.90
}
### POST: /v1/customers
EN_US: Insert a new Customer in "customers" table
PT_BR: Insere um novo Cliente na tabela "customers"
{
"name": "Maria Aparecida de Souza",
"cpf": "81258705044",
"email": "mariasouza@email.com"
}
### POST: /v1/orders
EN_US: Insert a new Order in "orders" table and insert it's Items "order_items" table
PT_BR: Insere um novo Pedido na tabela "orders" e insere seus itens na tabela "order_items"
{
"status": "CONCLUDED",
"total": 189.80,
"buyer": {
"id": 1,
"name": "Maria Aparecida de Souza",
"cpf": "81258705044",
"email": "mariasouza@email.com"
},
"items": [
{
"amount": 1,
"price_unit": 109.90,
"total": 109.90,
"product": {
"id": 1,
"sku": 8552515751438644,
"title": "Casaco Jaqueta Outletdri Inverno Jacquard"
}
},
{
"amount": 1,
"price_unit": 79.90,
"total": 79.90,
"product": {
"id": 2,
"sku": 8552515751438645,
"title": "Camiseta Colcci Estampada Azul"
}
}
]
}
### PUT: /v1/orders/{order_id}
EN_US: Set the order's status as "CANCELED" in "orders" table
PT_BR: Atualiza o status do Pedido como "CANCELED" na tabela "orders"
{
"order_id": 3,
"status": "CANCELED"
}
### GET Routes
#### GET: /v1/products/
EN_US: Get all products in "products" table
PT_BR: Traz todos produtos da tabela "products"
#### GET: /v1/customers/
EN_US: Get all customers in "customers" table
PT_BR: Traz todos os clientes da tabela "customers"
#### GET: /v1/orders/
EN_US: Get all orders in "orders" table
PT_BR: Traz todos pedidos da tabela "orders"