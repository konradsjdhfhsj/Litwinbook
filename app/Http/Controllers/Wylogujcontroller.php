<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Wylogujcontroller extends Controller
{
    public function wyloguj(){
session_start();

$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 20,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

header("Location: /Litwinbook");
exit;

    }
}
