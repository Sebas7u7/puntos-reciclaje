<?php
require_once (__DIR__ . '/../../../logica/Solicitud.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$id = $_POST['id'];
$fechaProgramada = !empty($_POST['fecha_programada']) ? $_POST['fecha_programada'] : null;
$estado = $_POST["estado"] ?? 'pendiente';

// Validación: la fecha programada debe ser posterior a la actual
if ($fechaProgramada) {
    $ahora = date('Y-m-d H:i');
    if ($fechaProgramada <= $ahora) {
        $_SESSION['error_programar'] = 'La fecha y hora programada deben ser posteriores a la fecha y hora actual.';
        header("Location: verSolicitudes.php");
        exit();
    }
    $estado = "programado";
}


$descripcion_proceso = isset($_POST['descripcion_proceso']) ? trim($_POST['descripcion_proceso']) : null;
$solicitud = new Solicitud();
$solicitud->actualizar($id, $fechaProgramada, $estado, $descripcion_proceso);

// Redirigir de vuelta a la tabla de solicitudes para ver los cambios
header("Location: verSolicitudes.php");
exit();
?>