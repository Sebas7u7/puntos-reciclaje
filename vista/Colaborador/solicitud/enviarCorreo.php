<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once (__DIR__ . '/../../../vendor/autoload.php');
require_once (__DIR__ . '/../../../logica/Cuenta.php');
$cuenta = new Cuenta();

$correo = $_SESSION["email_pending"];

// 3. 이메일 링크 생성
$host = $_SERVER['HTTP_HOST']; // 자동 감지
$link = "http://$host/puntos-reciclaje/vista/activacionCuenta/activarCuenta.php?cuenta=" . $_SESSION["email_pending"];

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

    $mail->setFrom('PConfirmacion1@gmail.com', 'EcoGestor');
    $mail->addAddress($correo);

    $mail->isHTML(true);
    $mail->Subject = 'Solicitud programada de manera exitosa';
    $mail->Body = 'Una solicitud fue programada el día ' . $_SESSION["dia"] . '.';

    $mail->send();
    $_SESSION['message_type'] = 'success';
    $_SESSION['message'] = 'Una solicitud fue programada el día ' . $_SESSION["dia"] . '.';
} catch (Exception $e) {
    $_SESSION['message_type'] = 'danger';
    $_SESSION['message'] = "Error al enviar correo: {$mail->ErrorInfo}.";
}
    header("Location: /puntos-reciclaje/vista/Colaborador/indexColaborador.php"); // Vuelve al login
    exit();
?>