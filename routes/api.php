<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/external-books' , 'BookController@fetchByName');
Route::post('/v1/books' , 'BookController@create');
Route::get('/v1/books' , 'BookController@getAllBooks');
Route::patch('/v1/books/{id}' , 'BookController@update');
Route::delete('/v1/books/{id}' , 'BookController@delete');
Route::get('/v1/books/{id}' , 'BookController@view');

