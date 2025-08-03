<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Uzytkownik;

class RejestracjaController extends Controller
{
    public function rejestracja(Request $request)
    {
        $request->validate([
            'nazwa' => 'required|string|max:255',
            'email' => 'required|email|unique:uzytkownicy,email',
            'haslo' => 'required|string|min:6',
        ]);

        $nazwa = htmlspecialchars($request->input('nazwa'));
        $email = htmlspecialchars($request->input('email'));
        $haslo = Hash::make($request->input('haslo'));

        DB::table('uzytkownicy')->insert([
            'nazwa' => $nazwa,
            'email' => $email,
            'haslo' => $haslo,
        ]);

        DB::table('chats')->insert([
            'nazwa' => $nazwa,
        ]);

        return redirect('/Litwinbook')->with('success', 'Rejestracja zakończona sukcesem.');
    }
}
