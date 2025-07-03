<?php

require_once(__DIR__ . '/../../../logica/Comentario.php');
require_once(__DIR__ . '/../../../logica/Usuario.php');
session_start();
$contenido = $_POST['contenido'] ?? '';
$padreId = $_POST['padre'] ?? null;
if (!$padreId){
    $padreId = "NULL";
}
$idUsuario = $_SESSION['usuario']->getIdUsuario(); // ya lo tienes
$fecha = date("Y-m-d H:i:s");

if (trim($contenido) === '') {
    http_response_code(400);
    echo "Error: El contenido está vacío.";
    exit;
}

// echo $contenido;
// echo $padreId;
// echo $idUsuario;
$comentario = new Comentario();
$comentario->guardar($contenido,$fecha,$idUsuario, $padreId);

echo "Respuesta guardada correctamente.";
?>