<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;
use App\Book;
use App\Author;


class BookController extends Controller
{

    public function fetchByName(Request $request){

        $client = new Client();

        $query = $request->query('name');

        $promise = $client->getAsync('https://www.anapioficeandfire.com/api/books',[
                'query' => ['name' => $query]
            ])->then(
            function ($response) {
                return $response->getBody()->getContents();
            }, function ($exception) {
                return $exception->getMessage();
            }
        );

        $response = $promise->wait();

        $fetchedData = json_decode($response);

        // print_r($fetchedData);die();

        $data = [];

        $i = 0;
        while($i < count($fetchedData)){
            $data[$i]['name'] = $fetchedData[$i]->name;
            $data[$i]['isbn'] = $fetchedData[$i]->isbn;
            $data[$i]['authors'] = $fetchedData[$i]->authors;
            $data[$i]['number_of_pages'] = $fetchedData[$i]->numberOfPages;
            $data[$i]['publisher'] = $fetchedData[$i]->publisher;
            $data[$i]['country'] = $fetchedData[$i]->country;
            $data[$i]['release_date'] = date("Y-m-d", strtotime($fetchedData[$i]->released));
            $i++;
        }

        return response()->json(["status_code"=> 200,"status"=>"success","data"=> $data] , 200);

    }

    public function create(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'isbn' => 'required|string',
            "authors.*"  => "required|string",
            'country' => 'required|string',
            'number_of_pages' => 'required|integer',
            'publisher' => 'required|string',
            'release_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors() , 422);
        }

        $request = $request->only('name', 'isbn', 'authors' , 'country' , 'number_of_pages' , 'publisher' ,"release_date" );

    //  Create new Book
        $newBook = Book::create([
            'name' => $request['name'],
            'isbn' => $request['isbn'],
            'country' => $request['country'],
            'number_of_pages' => $request['number_of_pages'],
            'publisher' => $request['publisher'],
            'release_date' => $request['release_date']
        ]);

        if($newBook){
            $data = [];
            foreach ($request['authors'] as $author) {
                $data[] = [
                    'book_id'  => $newBook->id,
                    'author' => $author
                ];
            }

            // Create Author
            $newAuthor = Author::insert($data);
            if($newAuthor){
                return response()->json(["status_code"=> 201,"status"=>"success","data"=> [$request]]);
            }
        }

        return response()->json(["status_code"=> 500,"status"=>"false","data"=> []] );
    }


    //fetch authors
    private function fetchAuthors($book){

        $authors = $book->authors()->select('author')->get();

        $temp = array();

        foreach ($authors as $author){
            array_push( $temp , $author->author);
        }

        return $temp;
    }


    // Fetch All books
    public function getAllBooks(){
        $books = Book::all();

        foreach($books as $book){

            $book['authors'] = $this->fetchAuthors($book);

        }
        return response()->json(["status_code"=> 200,"status"=>"success","data"=> $books]);
    }

    // Update a book :id
    public function update(Request $request , $id){
        $book = Book::find($id);

        if(!$book){
            return response()->json(["status_code"=> 404,"status"=>"false","data"=> []] );
        }

        $fields = $request->all();
        $authors = [];

        if (array_key_exists("authors",$fields)){
            $tempAuthors = $fields['authors'];
            for($i = 0 ; $i <  count($tempAuthors); $i++){
                $authors[$i]['book_id'] = $id;
                $authors[$i]['author'] = $tempAuthors[$i];
            }
            unset($fields['authors']);
        }


        if(count($authors) > 0){
            $book->authors()->delete();
            $author = new Author();
            $author->insert($authors);
        }

        $bookUpdate = $book->update($request->all());


        if($bookUpdate){
            $book['authors'] = $this->fetchAuthors($book);
            return response()->json(["status_code"=> 200,"status"=>"success","data"=> $book]);
        }

        return response()->json(["status_code"=> 500,"status"=>"false","data"=> []]);

    }

    //Delete a book
    public function delete($id){
        $book = Book::find($id);

        if(!$book){
            return response()->json(["status_code"=> 404,"status"=>"false","data"=> []]);
        }
        $name = $book->name;

        $delete = $book->delete();
        $authorDelete = $book->authors()->delete();

        if($delete || $authorDelete){
            return response()->json(["status_code"=> 204,"status"=>"success","message" => "The book ". $name ." was deleted successfully","data"=> []]);
        }
    }

    public function view($id){
        $book = Book::find($id);
        if(!$book){
            return response()->json(["status_code"=> 404,"status"=>"false","data"=> []]);
        }

        $book['authors'] = $this->fetchAuthors($book);
        return response()->json(["status_code"=> 200,"status"=>"success","data"=> $book]);
    }
}
