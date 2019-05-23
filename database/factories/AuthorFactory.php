<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Author;
use App\Book;

use Faker\Generator as Faker;

$factory->define(Author::class, function (Faker $faker) {
    return [
        'book_id' => function () {
            return factory(\App\Book::class)->create()->id;
        },
        'author' => $faker->name
    ];
});
