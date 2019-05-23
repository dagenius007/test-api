<?php

namespace Tests\Unit;

use App\Book;
use App\Author;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BookTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_create_book() {
        $data = [
            'name' => 'A Game of Thrones',
            'isbn' => "1234-5555-6666",
            'authors' => ["John Doe" , "Jon Snow"],
            'country' => 'Nigeria',
            'number_of_pages'=> 600,
            'publisher' =>  "Greyworm Snow",
            'release_date' => "2019-02-12"
        ];

      $this->json('POST', 'api/v1/books', $data)->assertStatus(201)
            ->assertJson([
                "status_code"=> 201,
                "status" => "success",
                "data" => [$data]
            ]);
    }


    public function test_can_update_book() {
        $book = factory(Book::class)->create();
        for ($i= 0; $i < 2; $i++){
            factory(Author::class)->create([
                'book_id' => $book->id
            ]);
        }

        $data = [
            'name' => 'A book to Update',
            'isbn' => "1234-666-999",
            'authors' => ["Jerry Bayo" , "Damilola Dolapo"],
            'country' => 'England',
            'number_of_pages'=> 700,
            'publisher' =>  "Grey Arya",
            'release_date' => "2019-02-22"
        ];

        $response = $this->json('PATCH', 'api/v1/books/'.$book->id, $data)->assertStatus(200)
            ->assertJson([
                "status_code"=> 200,
                "status" => "success",
                "data" => $data
            ]);
    }
    public function test_can_show_book() {
        $book = factory(Book::class)->create();
        $authors = [];
        for ($i= 0; $i < 2; $i++){
            $author =
            factory(Author::class)->create([
                'book_id' => $book->id
            ]);
            $authors[$i] = $author->author;
        }

        $book->authors = $authors;

        $this->json('GET', 'api/v1/books/'.$book->id)->assertStatus(200)
            ->assertJson([
                "status_code"=> 200,
                "status" => "success",
                "data" => $book->toArray()
            ]);
    }

    public function test_can_delete_book() {
        $book = factory(Book::class)->create();
        for ($i= 0; $i < 2; $i++){
            factory(Author::class)->create([
                'book_id' => $book->id
            ]);
        }

     $this->json('DELETE', 'api/v1/books/'.$book->id)->assertStatus(204);
    }

    public function test_can_list_book() {
        $books = [];

        for( $i = 0;$i < 4 ; $i++){

            $book = factory(Book::class)->create();

            $authors = [];
            for ($j= 0; $j < 2; $j++){
                $author =
                factory(Author::class)->create([
                    'book_id' => $book->id
                ]);

                $authors[$j] = $author->author;
            }

             $book->authors = $authors;

             $books[$i] = $book->toArray();
        }


        $this->json('GET', 'api/v1/books/')->assertStatus(200)
            ->assertJson([
                "status_code"=> 200,
                "status" => "success",
                "data" => $books
            ]);
    }
}
