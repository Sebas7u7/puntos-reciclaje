<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/../../logica/Cuenta.php');

if (isset($_POST["cambioClave"])) {
    $correo = $_POST["correo"];

    // Validación básica: solo correo
    if (empty($correo)) {
        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = 'El campo correo es obligatorio.';
        header("Location: cambioClave.php");
        exit();
    }

    // Validar que el correo exista en la base de datos
    $cuenta = new Cuenta();
    $conexion = new Conexion();
    $conexion->abrirConexion();
    $cuentaDAO = new CuentaDAO();
    $cuentaData = $cuentaDAO->consultarPorCorreo($conexion, $correo);
    $conexion->cerrarConexion();
    if (!$cuentaData) {
        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = 'El correo ingresado no está registrado.';
        header("Location: cambioClave.php");
        exit();
    }
    // Guardar correo en sesión y redirigir a autenticarCorreo.php para enviar el correo
    $_SESSION["email_pending"] = $correo;
    header("Location: /puntos-reciclaje/vista/cambioClave/autenticarCorreo.php");
    exit();
} else {
    header("Location: cambioClave.php");
    exit();
}
?>