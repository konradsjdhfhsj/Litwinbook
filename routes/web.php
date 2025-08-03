<?php

use App\Http\Controllers\Dodajpostcontroller;
use App\Http\Controllers\Issuecontroller;
use App\Http\Controllers\Logowaniecontroller;
use App\Http\Controllers\Profilcontroller;
use App\Http\Controllers\Rejestracjacontroller;
use App\Http\Controllers\Wylogujcontroller;
use App\Http\Controllers\Zmiananazwycontroller;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::match(['get', 'post'], '/Litwinbook', function () {return view('insex');});
Route::match(['get', 'post'], '/Litwinpost', function () {return view('lb');});
//Route::match(['get', 'post'], '/Issue', function () {return view('issue');});
Route::match(['get', 'post'], '/rejestracja', [Rejestracjacontroller::class, 'rejestracja']);
Route::match(['get', 'post'], '/logowanie', [Logowaniecontroller::class, 'logowanie']);
Route::match(['get', 'post'], '/wyloguj', [Wylogujcontroller::class, 'wyloguj']);
//Route::match(['get', 'post'], '/dodajpost', [Dodajpostcontroller::class, 'dodajpost']);
Route::match(['get', 'post'], '/profil', [Profilcontroller::class, 'profil']);
Route::match(['get', 'post'], '/zmiananazwy', [Zmiananazwycontroller::class, 'zmiananazwy']);
//Route::match(['get', 'post'], '/issues', [Issuecontroller::class, 'issue']);
Route::get('/Issue', function () {
    return view('issue');
});

Route::post('/issues', [Issuecontroller::class, 'issue'])->name('issues.store');
Route::match(['get','post'], '/dodajpost', [Dodajpostcontroller::class, 'dodajpost'])->name('post.store');
