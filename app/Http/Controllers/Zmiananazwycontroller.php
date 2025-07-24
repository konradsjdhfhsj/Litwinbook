<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Zmiananazwycontroller extends Controller
{
    public function zmiananazwy(){
if($_POST){
$conn = mysqli_connect('localhost', 'root', '', 'litwinbook');


            $nowanazwa = $_POST['new_user_name'] ?? '';
            $staranazwa = $_POST['user_name'] ?? '';
            $haslo = $_POST['haslo'] ?? '';

            $query = "SELECT * FROM urzytkownicy WHERE (nazwa = ? OR email = ?) AND haslo = ?";
            $query = $conn->prepare($query);
            $query->bind_param("sss", $staranazwa, $staranazwa, $haslo);
            $query->execute();


            $result = $query->get_result();

            if($result -> num_rows >0){
            $query1 = "UPDATE urzytkownicy SET nazwa = ? WHERE (nazwa = ? OR email = ?)";
            $query1 = $conn->prepare($query1);
            $query1->bind_param("sss", $nowanazwa, $staranazwa, $staranazwa);
            if($query1->execute()){
                header('Location: /Litwinbook');  //tu nie jestem pewien lokacji
            }
            $query2 = "UPDATE post SET imie = ? WHERE imie = ?";
            $query2 = $conn->prepare($query2);
            $query2->bind_param("ss", $nowanazwa, $staranazwa);
            if($query2->execute()){
                header('Location: /Litwinbook'); //tu tesz
            }       
            } else {
                echo"Niestety nie mozemy zmienic nazwy . Prawdopodobnje podales bledne dane";
            }
        }
    }
}
