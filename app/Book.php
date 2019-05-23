<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'name' , 'isbn', 'country', 'number_of_pages', 'publisher' , 'release_date'
    ];

    public $timestamps = false;


    public function authors(){
        return $this->hasMany('App\Author');
    }
}
