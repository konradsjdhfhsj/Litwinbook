<?php
// Załaduj PHPMailer (pobierz z https://github.com/PHPMailer/PHPMailer i dołącz do swojego projektu)
// Tu zakładam, że masz PHPMailer w katalogu 'PHPMailer/src'

/*use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Dane do SMTP Gmail
$mailHost = 'smtp.gmail.com';
$mailPort = 587;
$mailUsername = 'zssss@gmail.com';     
$mailPassword = 'uads dsab gdre hbgf';
$mailFrom = 'zssss@gmail.com';
$mailFromName = 'Mail';

$email = $_POST['email'] ?? '';

$recipient = $email;

$mail = new PHPMailer(true);

try {
    // Konfiguracja SMTP
    $mail->isSMTP();
    $mail->Host = $mailHost;
    $mail->SMTPAuth = true;
    $mail->Username = $mailUsername;
    $mail->Password = $mailPassword;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $mailPort;

    // Nadawca i odbiorca
    $mail->setFrom($mailFrom, $mailFromName);
    $mail->addAddress($recipient);

    // Treść maila
    $mail->isHTML(true);
    $mail->Subject = 'Test Mail';
    $mail->Body    = '<p>Powinno działać. Jak działa to pozdrawiam serdecznie!</p>';

    // Wyślij
    $mail->send();
    echo 'Wiadomość została wysłana!';
} catch (Exception $e) {
    echo "Nie udało się wysłać maila. Błąd: {$mail->ErrorInfo}";}

