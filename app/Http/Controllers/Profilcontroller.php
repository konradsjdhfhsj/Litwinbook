<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Profilcontroller extends Controller
{
    public function profil(){
if ($_POST) {
    session_start();
$conn = mysqli_connect('localhost', 'root', '', 'litwinbook');

    $uploadDir = 'avataren/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$opis = trim($_POST['opis'] ?? '');
$staranazwa = $_SESSION['nazwa'] ?? '';
$nowanazwa = trim($_POST['nazwa'] ?? '');
$zdjPath = null;

// Sprawdzenie nowego zdj�cia
if (isset($_FILES['avat']) && $_FILES['avat']['error'] === UPLOAD_ERR_OK) {
    $tmpName = $_FILES['avat']['tmp_name'];
    $originalName = basename($_FILES['avat']['name']);
    $safeName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
    $targetPath = $uploadDir . $safeName;

    if (move_uploaded_file($tmpName, $targetPath)) {
        $zdjPath = $targetPath;
    }
}

// Budowanie dynamicznego zapytania
$fields = [];
$params = [];
$types = '';

if ($zdjPath !== null) {
    $fields[] = 'avat = ?';
    $params[] = $zdjPath;
    $types .= 's';
}

if ($opis !== '') {
    $fields[] = 'opis = ?';
    $params[] = htmlspecialchars($opis);
    $types .= 's';
}

if ($nowanazwa !== '') {
    $fields[] = 'nazwa = ?';
    $params[] = htmlspecialchars($nowanazwa);
    $types .= 's';
}

if (empty($fields)) {
    header("Location: lb.php");
    exit;
}

$query = "UPDATE urzytkownicy SET " . implode(', ', $fields) . " WHERE nazwa = ?";
$params[] = $staranazwa;
$types .= 's';

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    if ($nowanazwa !== '') {
        $_SESSION['nazwa'] = $nowanazwa;
    }
    header("Location: /Litwinpost");
    exit;
}

}
    }
}
