<?php
require_once (__DIR__ . '/../../../logica/Solicitud.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$id = $_POST['id'];
$fechaProgramada = !empty($_POST['fecha_programada']) ? $_POST['fecha_programada'] : null;
$estado = $_POST["estado"];
if($fechaProgramada){
    $estado = "programada";
}
$solicitud = new Solicitud();
$solicitud->actualizar($id,$fechaProgramada,$estado);

$_SESSION["email_pending"] = $_POST["correoUsuario"];
$_SESSION["dia"] = $fechaProgramada;
header("Location: /puntos-reciclaje/vista/Colaborador/solicitud/enviarCorreo.php");
exit();
?>