PHP Fullstack - API em Laravel com Vue no frontend
=====

Arquitetura client server com uma API em Laravel no backend, e frontend em Vue.

Setup
=====

Instale o [Docker](https://docs.docker.com/get-started/).

Abra o terminal na raiz da aplicação e digite o comando:
```sh
docker-compose up -d
```

![docker_compose](_readme_images/1.png)

Depois de concluído, o banco MYSQL já está criado e os comandos do artisan de `migrate` e `db:seed` já devem ter sido rodados automaticamente. A API já está pronta e pode ser acessada pela rota `http://localhost:8001/api/`

Para testar se está funcionando pode dar um `GET` na rota `/`:

![ping](_readme_images/2.png)

Rotas
=====

```
+--------+----------+-----------------+------+----------------------------------------------------+------------+
| Domain | Method   | URI             | Name | Action                                             | Middleware |
+--------+----------+-----------------+------+----------------------------------------------------+------------+
|        | GET|HEAD | /               |      | Closure                                            | web        |
|        | GET|HEAD | api             |      | Closure                                            | api        |
|        | GET|HEAD | api/cep         |      | App\Http\Controllers\CepController@getCep          | api        |
|        |          |                 |      |                                                    | auth       |
|        | POST     | api/certificado |      | App\Http\Controllers\SecController@readCertificate | api        |
|        |          |                 |      |                                                    | auth       |
|        | DELETE   | api/delete/{id} |      | App\Http\Controllers\UserController@destroy        | api        |
|        |          |                 |      |                                                    | auth       |
|        | GET|HEAD | api/index       |      | App\Http\Controllers\UserController@index          | api        |
|        |          |                 |      |                                                    | auth       |
|        | GET|HEAD | api/login       |      | App\Http\Controllers\AuthController@login          | api        |
|        | GET|HEAD | api/me          |      | App\Http\Controllers\AuthController@me             | api        |
|        |          |                 |      |                                                    | auth       |
|        | POST     | api/store       |      | App\Http\Controllers\UserController@store          | api        |
|        |          |                 |      |                                                    | auth       |
|        | POST     | api/update/{id} |      | App\Http\Controllers\UserController@update         | api        |
|        |          |                 |      |                                                    | auth       |
|        | GET|HEAD | api/user        |      | Closure                                            | api        |
|        |          |                 |      |                                                    | auth:api   |
|        | GET|HEAD | api/user/{id}   |      | App\Http\Controllers\UserController@show           | api        |
|        |          |                 |      |                                                    | auth       |
+--------+----------+-----------------+------+----------------------------------------------------+------------+

```

- Antes de consumir a API é necessário logar chamando a rota de login com o usuário `login@email.com` senha `123456` (esses dados estão parametrizados no `.env`)
- O login irá retornar um token que deve ser usado para autenticar as demais rotas.
- Também está disponível na pasta `postman` uma collection e um environment do Postman com todas as rotas. Essa collection já está pronta para adicionar automaticamente o token retornado pelo login em todas as requisições.

![postman1](_readme_images/8.png)

![postman2](_readme_images/3.png)

![postman3](_readme_images/7.png)

Teste unitários
=====

Para rodar os testes, será necessário entrar no container do Docker para rodar o comando.

- Para listar os containers use o comando `docker ps`
![docker_ps](_readme_images/4.png)

- O nome do container é `laravel-fullstack_apache_with_php_1`
- Pode ser acessado com o comando `docker exec -it laravel-fullstack_apache_with_php_1 bash`

- Para rodar os testes, rode o comando `php artisan test`
![test](_readme_images/5.png)

DB
=====
![db](_readme_images/6.png)
Configuração do DB. User `admin` senha `1234` (parametrizado no .env)

Screenshots
=====

![screenshot1](_readme_images/9.png)

![screenshot1](_readme_images/10.png)

![screenshot1](_readme_images/11.png)

![screenshot1](_readme_images/12.png)

![screenshot1](_readme_images/13.png)
