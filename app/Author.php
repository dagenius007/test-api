<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $fillable = ['book_id','author'];

    public $timestamps = false;
    
    public function book(){
        return $this->belongsTo('App\Book');
    }
}
