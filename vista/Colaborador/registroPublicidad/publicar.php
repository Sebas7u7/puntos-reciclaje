<?php
require_once (__DIR__ . '/../../../logica/Colaborador.php');
require_once (__DIR__ . '/../../../logica/Publicacion.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_POST["registrar_publicidad"])) {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $tipo = $_POST['tipo'];
    $fecha_publicacion = $_POST['fecha_public']; // 이름이 다르니 맞춰줌
    $enlace = $_POST['enlace'];

    $publicacion = new Publicacion();
    $colaborador = new Colaborador();
    $publicacion->registrar($titulo, $descripcion, $tipo, $fecha_publicacion, $enlace, $_SESSION["colaborador"] -> getIdColaborador());

    $_SESSION['message_type'] = 'success';
    $_SESSION['message'] = 'campañas / noticias publicado exitosamente.';
    header("Location: /puntos-reciclaje/vista/Colaborador/registroPublicidad/registrarPublicidad.php");
    exit();
}
?>