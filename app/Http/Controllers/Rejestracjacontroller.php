<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Rejestracjacontroller extends Controller
{
    public function rejestracja(){
        session_start();

        if($_POST){
        $conn = mysqli_connect('localhost', 'root', '', 'litwinbook');
        $nazwa = htmlspecialchars($_POST['nazwa']??'');
        $email = htmlspecialchars($_POST['email']??'');
        $haslo = $_POST['haslo'] ?? '';
        $avat = $_POST['avat'] ?? '';

        $spr = $conn->prepare("SELECT * FROM urzytkownicy WHERE (nazwa = ? OR email = ?)");
        $spr->bind_param('ss', $nazwa, $email);
        $spr->execute();

        $wynik = $spr->get_result();

        if($wynik -> num_rows >0){
            echo"Niestety nie mozna dokonczyc rejestracji.taki urzytkownik jusz istnieje";
        } else {
             $query = $conn->prepare("SELECT * FROM urzytkownicy WHERE email =?");
            $query->bind_param("s", $email);
            $query->execute();
            $result = $query->get_result();
                //var_dump($query);
            if ($result->num_rows > 0) {
                echo "<script>alert('Email istnieje. Zmień email.'); window.location.href='index.php';</script>";
                exit;
            }
            else {
                $stmt = $conn->prepare("INSERT INTO urzytkownicy(email, nazwa, haslo)VALUES(?, ?, ?)");
                $stmt1 = $conn->prepare("INSERT INTO chats(nazwa)VALUES(?)");
                $stmt->bind_param("sss",$email, $nazwa, $haslo);
                $stmt1->bind_param("s", $nazwa);
                if($stmt->execute() && $stmt1->execute()){
                header("Location: /Litwinbook");
                }
            }
        }
    
    }
    }
}
