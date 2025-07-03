<?php
require_once (__DIR__ . '/../../../logica/Colaborador.php');
require_once (__DIR__ . '/../../../logica/Punto_recoleccion.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_POST["asignarP_recolect"])) {
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $latitud = $_POST['latitud'];
    $longitud = $_POST['longitud'];
    $estado = $_POST['estado'];

    $punto = new Punto_recoleccion();
    $colaborador = new Colaborador();

    $punto->registrar(
        $nombre,
        $direccion,
        $latitud,
        $longitud,
        $estado,
        $_SESSION["colaborador"]->getIdColaborador()
    );
    $_SESSION['message_type'] = 'success';
    $_SESSION['message'] = 'punto recoleccion registrado exitosamente.';
    header("Location: /puntos-reciclaje/vista/Colaborador/registro_P_Recolect/registrarP_Recolect.php");
    exit();
}
?>