<p align="center">Goomer Lista Rango</p>
API para gerenciamento de restaurante e produtos do restaurante.


### Instalação

- Clonar o projeto
```bash
git clone https://github.com/bandrade93/goomer-lista-rango.git
cd goomer-lista-rango
```
- Instalação de pacotes do composer:
```bash
docker run --rm -v $(pwd)/goomer-lista-rango:/app composer:latest install
```

- Instalação das dependencias
```bash
composer (pasta vendor)
```

- Subir containers com o docker-compose (esse processo leva em média de 30 a 60 segundos depois do download das imagens)
```bash
docker-compose up -d
```

- Copiar arquivo .env
```bash
cp .env.example .env
```

- Make Laravel key;
```bash
php app artisan key:generate
```

- Instalação da base de dados (Depois do container do MySql iniciar):
```bash
php artisan migrate
```

- Gerar dados na tabela de categorias:
```bash
php artisan db:seed
```

- Instalação PHPUnit
```bash
docker run --rm -it -v $(pwd)/goomer-lista-rango-:/app phpunit/phpunit:latest
```

- Rodar testes unitários:
```bash
./vendor/bin/phpunit
```

Testes da api feitos pelo Insomnia




### Sobre

#### Requerimentos

- Essa API trabalha com PHP (^7.4)
- [Docker](https://docs.docker.com/install/) (^20.10.5)
- [docker-compose](https://docs.docker.com/compose/install/) (^1.29.0)

#### Docker images
- [php:7.4-apache](https://hub.docker.com/_/php)
- [mysql:5.7](https://hub.docker.com/_/mysql)
- [composer:latest](https://hub.docker.com/_/composer) 
- [phpunit:phpunit](https://hub.docker.com/r/phpunit/phpunit)

#### Files for review
```bash
app/
├── Categorias.php
├── Produtos.php
├── Restaurantes.php
├── Http
│   ├── Controllers
│   │   ├── ProdutoController.php
│   │   ├── RestauranteController.php
│   ├── DB
│   │   ├── Produto.php
│   │   ├── Restaurante.php

database/
├── migrations
│   ├── 2021_05_04_015418_create_categorias_table.php
│   ├── 2021_05_04_131542_create_restaurantes_table.php
│   ├── 2021_05_04_131843_create_funcionamento_restaurantes_table.php
│   ├── 2021_05_04_132206_create_produtos_table.php
│   └── 2021_05_04_134032_create_promocao_produtos_table.php
└── seeds
    ├── CategoriaSeeder.php
    └── DatabaseSeeder.php
public/
├── json
│   ├── insert_produtos.json
│   └── insert_restaurantes.json
└── 
tests/
└── Feature
    ├── ProdutosTest.php
    └── RestaurantesTest.php
docker-compose.yml
Dockerfile
```

## Desafios/dificuldades

A ferramenta usada para o desenvolvimento da aplicação foi o laravel, que é um dos frameworks mais robustos e usados de PHP no mercado.

O desafio em usar o laravel, foi não utilizar o Eloquent para as intruções de banco de dados, pois no desafio proposto, era descrito para não utilizar ORM.

Uma dificuldade encontrada, foi na definição da estrutura para as promoções dos produtos.

Pode existir uma promoção pode ser por tempo indeterminado, como por exemplo, "Double chopp até as 20h todos os dias", isso acontece na hamburgueria Lets Eat.
Também as promoções por período, por exemplo, "Toda quinta e domingo, o lanche comprado, sai pela metade do preço na proxima vez que voltar.", e não tem um horário pré estimulado para tal promoção, e nem datas definidas.

## Melhorias

Ao meu ver, como o projeto foi desenvolvido com o laravel, seria interessante a utilização do Eloquent para as ações no banco de dados.

Uma melhoria que eu vejo na estruturação, é na parte de promoção dos produtos.

Criar uma tabela de tipo de promoção, daria para organizar bem, definindo se é uma promoção temporária, com data de inicio e data de fim, se é uma promoção permanente, sem data de fim.

Outra melhoria, seria atrelar o horário das promoções, para o horário de funcionamento do restaurante.



