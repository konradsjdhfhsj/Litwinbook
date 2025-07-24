<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Likecontroller extends Controller
{
    public function like(){
   header('Content-Type: application/json');

$conn = mysqli_connect('localhost', 'root', '', 'litwinbook');
if (!$conn) {
    echo json_encode(['success' => false, 'error' => 'Błąd połączenia z bazą danych.']);
    exit;
}

$postId = $_POST['post_id'] ?? 0;

if ($postId > 0) {
    $stmt = $conn->prepare("UPDATE post SET `like` = `like` + 1 WHERE id = ?");
    $stmt->bind_param("i", $postId);

    if ($stmt->execute()) {
        $stmt->close();

        $res = $conn->query("SELECT `like` FROM post WHERE id = $postId");
        $row = $res->fetch_assoc();
        $likes = $row['like'] ?? 0;

        echo json_encode(['success' => true, 'likes' => $likes]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Nie udało się zaktualizować lajków.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Nieprawidłowy ID posta.']);
}

$conn->close();
    }
}
