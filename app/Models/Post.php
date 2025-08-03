<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Post extends Model
{
    protected $table = 'post';

    protected $fillable = [
        'post',
        'zdj',
        'imie',
        'komentarz',
        'data',
        'like'
    ];
}
