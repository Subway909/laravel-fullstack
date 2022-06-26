PHP Fullstack - Laravel backend with Frontend using Vue
=====

Client server architecture: Laravel backend API and frontend made with Vue

<sup><sub>This was made at the request of a company that i was in the procces of interviewing at the time (and ended up working there for a while). Their instructions are on a pdf file inside the `_part1` folder (portuguese only), but it was required to build an API and a frontend (CRUD) </sub></sup>

Screenshots
=====

![screenshot1](/_readme_images/en-US/9.png)
Click the icon at the top to get instructions on how to log in

![screenshot1](/_readme_images/en-US/10.png)

![screenshot1](/_readme_images/en-US/11.png)

![screenshot1](/_readme_images/en-US/12.png)

![screenshot1](/_readme_images/en-US/13.png)

Setup
=====

Install [Docker](https://docs.docker.com/get-started/).

Open the terminal, go to the app folder and type:
```sh
docker-compose up -d
```

![docker_compose](/_readme_images/en-US/1.png)

After Docker finishes building the project, the database is created and the Artisan `migrate` and `db:seed` commands are executed automatically to populate the database. Now the API is ready and can be reached by the URL: `http://localhost:8001/api/`   

To check if it's working, you can `GET` the route `/`:

![ping](/_readme_images/en-US/2.png)

Routes
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

- Before consuming the API, it's necessary to login by calling the login route with user `login@email.com` password `123456` (this data is set via `.env` file)
- That way the login will return the `bearer token`, which will be used to authenticate all the other API methods
- Also available in the folder `_postman`, the Postman collection and environment. This collection is set to add the token returned by the `login`, so you're authenticated

![postman1](/_readme_images/pt-BR/8.png)

![postman2](/_readme_images/en-US/3.png)

![postman3](/_readme_images/en-US/7.png)

Vue
=====

Inside the folder `sistema`, run `npm install` command (Node and NPM required). Após a instalação das dependências, rode o comando `npm run serve`. When ready, this command will return the url to access the Vue frontend:

![vue](/_readme_images/en-US/14.png)


Unit tests
=====
To run the unit tests, you have to ssh into the docker container:

- To list containers rype `docker ps`
![docker_ps](/_readme_images/en-US/4.png)

- You should see a container named `laravel-fullstack_apache_with_php_1`
- To ssh into it, type `docker exec -it laravel-fullstack_apache_with_php_1 bash`
- To run the tests, type `php artisan test`
![test](/_readme_images/pt-BR/5.png)

Database
=====
![db](/_readme_images/pt-BR/6.png)
Database configuration. User `admin` password `1234` (this data is set via `.env` file)
