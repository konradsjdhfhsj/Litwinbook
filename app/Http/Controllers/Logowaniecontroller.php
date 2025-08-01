<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Logowaniecontroller extends Controller
{
    public function logowanie(){
       session_start();
$conn = mysqli_connect('localhost', 'root', '', 'litwinbook');

        $user_login = $_POST['email'] ?? "";
        $user_password = $_POST['haslo'] ?? "";

        $sql = "SELECT * FROM urzytkownicy WHERE email = ? AND haslo = ?";
        $stmt = $conn->prepare($sql);


        $stmt->bind_param("ss", $user_login, $user_password);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            /*echo "Zalogowano pomyĹ›lnie!";
            echo "<script type='text/javascript'>alert('UdaĹ‚o siÄ™ zalogowaÄ‡ pomyĹ›lnie');</script>";*/
            $_SESSION['nazwa'] = $row['nazwa'];
            return redirect('/Litwinpost');
        } else {
            echo "Niepoprawny login lub hasĹ‚o.";
            echo "<script type='text/javascript'>alert('Nie udaĹ‚o siÄ™ zalogowaÄ‡');</script>";
        }

        $stmt->close();
        $conn->close();

    }
}