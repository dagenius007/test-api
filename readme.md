

# Test Api 

A test api performing CRUD operations with laravel(php) framework.

## Requirements

    *[Laravel](https://laravel.com/docs/5.8/installation)
    *MAMP or XAMP
    *[Postman(to test APIs)](https://www.getpostman.com/)
    *[Vscode](https://code.visualstudio.com/) or any editor of your choice

## Quick start 

Ensure requirements are meet before you clone or download the project.

Use `git clone https://github.com/dagenius007/test-api.git` to clone the project or you can download the project

After clone the project run `composer update` to install all packages

Create a clone of `.env.example` file and rename to it to `.env`

Create a database in your phpMyAdmin (you must have setup MAMP or XAMP on your pc)

configure `.env` file 
 > DB_HOST=127.0.0.1
 > DB_PORT=3306 or 8889(if you are running on MAMP)
 > DB_DATABASE=your-database
 > DB_USERNAME=your-phpmyadmin-username
 > DB_PASSWORD=your-phpmyadmin-password

Navigate to your root folder of your project in your terminal and run `php artisan migrate` to migrate the database

start your application by running `php artisan serve`.

The application should be visible on http://localhost:8000

If all is set then we are ready to test :smile: :thumbsup:


## Endpoints

### Fetch Books from External Api(Ice AND Fire API)
  HTTP Method : `GET`
  Route :  `http://localhost:8000/api/external-books?name=:query`
  Parameter : { "name" : query }


### Create New Book
  HTTP Method : `POST`
  Route :  `http://localhost:8000/api/v1/books`
  Parameter & Example Request : {
                                    "name": "My first book Book",
                                    "isbn": "123-3213243567",
                                    "authors": ["John Doe" , "Alli"],
                                    "number_of_pages": 350,
                                    "publisher": "Acme Books",
                                    "country": "Nigeria",
                                    "release_date": "2019-01-21"
                                }
### Get all books
  HTTP Method : `POST`
  Route :  `http://localhost:8000/api/v1/books`
  Parameter & Example Request : ---

### Update Book
  HTTP Method : `PATCH`
  Route :  `http://localhost:8000/api/v1/books/:id`
  Parameter & Example Request : {
                                    "name": "My Last Book",
                                    "isbn": "123-3213245655",
                                    "authors": ["Josh" , "Boy"],
                                    "number_of_pages": 450
                                }

### Delete Book
  HTTP Method : `DELETE`
  Route :  `http://localhost:8000/api/v1/books/:id`
  Parameter & Example Request :  ----

### Get Book
  HTTP Method : `GET`
  Route :  `http://localhost:8000/api/v1/books/:id`
  Parameter & Example Request :  ----


## Testing 
Sqlite database was used for testing .

run `php artisan migrate --database=sqlite` to setup up the database

run `comoposer test` to perform test 

Test file can be found in test/Unit/BookTest.php

