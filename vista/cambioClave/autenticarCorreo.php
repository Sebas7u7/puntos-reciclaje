<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once (__DIR__ . '/../../vendor/autoload.php');
require_once (__DIR__ . '/../../logica/Cuenta.php');
$cuenta = new Cuenta();

$correo = $_SESSION["email_pending"];

// 3. 이메일 링크 생성
$host = $_SERVER['HTTP_HOST']; // 자동 감지
$link = "http://$host/puntos-reciclaje/vista/cambioClave/cambiarClave.php?cuenta=" . $_SESSION["email_pending"];

// 4. 메일 보내기
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'PConfirmacion1@gmail.com';
    $mail->Password = 'pdkjyceqccxpzwzw';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('PConfirmacion1@gmail.com', 'Puntos de Reciclaje');
    $mail->addAddress($correo);

    $mail->isHTML(true);
    $mail->Subject = 'Activa tu cuenta';
    $mail->Body = "Hola, haz clic aquí para validar el cambio de tu contraseña: <a href='$link'>$link</a>";

    $mail->send();
    $_SESSION['message_type'] = 'success';
    $_SESSION['message'] = 'Correo enviado con enlace de validacion de cambio de contraseña.';
} catch (Exception $e) {
    $_SESSION['message_type'] = 'danger';
    $_SESSION['message'] = "Error al enviar correo: {$mail->ErrorInfo}.";
}
    header("Location: /puntos-reciclaje/index.php"); // Vuelve al login
    exit();
?>