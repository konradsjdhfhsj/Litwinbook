<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Dodajpostcontroller extends Controller
{
    public function dodajpost(){
header('Content-Type: application/json');

if ($_POST) {
    session_start();
    date_default_timezone_set("Europe/Berlin");

$conn = mysqli_connect('localhost', 'root', '', 'litwinbook');

    if (!$conn) {
        echo json_encode(["status" => "error", "message" => "Błąd połączenia z bazą danych."]);
        exit;
    }

    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $data = date("Y-m-d H:i:s");
    $nazwa = $_SESSION['nazwa'] ?? '';
    $opis = $_POST['opis'] ?? '';

    $zdjPath = '';

    if (isset($_FILES['zdj']) && $_FILES['zdj']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['zdj']['tmp_name'];
        $originalName = basename($_FILES['zdj']['name']);
        $safeName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
        $targetPath = $uploadDir . $safeName;

        if (move_uploaded_file($tmpName, $targetPath)) {
            $zdjPath = $targetPath;
        }
    }

    $stmt = $conn->prepare("INSERT INTO post(post, zdj, imie, data) VALUES(?, ?, ?, ?)");
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Błąd zapytania do bazy."]);
        exit;
    }

    $stmt->bind_param("ssss", $opis, $zdjPath, $nazwa, $data);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        echo json_encode(["status" => "success", "message" => "Post dodany pomyślnie."]);
        header("Location: /Litwinpost");
        exit;
    } else {
        echo json_encode(["status" => "error", "message" => "Błąd przy zapisie posta."]);
        exit;
    }
}

    }
}
