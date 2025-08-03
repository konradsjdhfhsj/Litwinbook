<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Urzytkownicy extends Model
{
    protected $table = 'urzytkowniy';

    protected $fillable = [
        'avat',
        'email',
        'nazwa',
        'haslo',
        'opis'
    ];
}
