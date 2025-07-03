<?php
session_start();
if (isset($_GET['cuenta'])) {
    require_once (__DIR__ . '/../../logica/Cuenta.php');
    $cuenta = new Cuenta();
    $cuenta -> activar($_GET['cuenta']);
    $_SESSION['message_type'] = 'success';
    $_SESSION['message'] = '¡Cuenta activada exitosamente! Ahora puedes iniciar sesión.';
}header("Location: /puntos-reciclaje/index.php");
?>