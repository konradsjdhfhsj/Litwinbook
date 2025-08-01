<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Filtrcontroller extends Controller
{
    public function filtr(){
        session_start();
        if($_POST){
            $conn = mysqli_connect('localhost', 'root', '', 'litwinbook');

            $osoba = $_POST['osoba'];

            $posty = $conn->prepare("SELECT * FROM post WHERE imie LIKE ?");
            $posty->bind_param("s", $osoba);
            $posty->execute();

            $wynikpost = $posty->get_result();

            while($row = $wynikpost->fetch_assoc()){
                echo"<h1>".$row['imie']."</h1>"."<br>";
                echo"<h1>".$row['post']."</h1>"."<br>";
                echo"<h1>".$row['data']."</h1>"."<br>";
            }
        }
    }
}
