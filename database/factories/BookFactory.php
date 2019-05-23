<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Book;
use Faker\Generator as Faker;



$factory->define(Book::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'isbn' => "123-555-666",
        'country' => $faker->country,
        'number_of_pages'=> $faker->randomDigit,
        'publisher' =>  $faker->name,
        'release_date' => $faker->unique()->date($format = 'Y-m-d', $max = 'now')
    ];
});
