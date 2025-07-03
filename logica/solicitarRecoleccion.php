<?php
// Lógica para procesar la solicitud de recolección puerta a puerta
require_once(__DIR__ . '/../persistencia/Conexion.php');
require_once(__DIR__ . '/../persistencia/SolicitudDAO.php');
require_once(__DIR__ . '/../persistencia/ColaboradorDAO.php');
require_once(__DIR__ . '/../logica/Usuario.php');

session_start();
$response = ["success" => false, "message" => ""];

if (!isset($_SESSION["usuario"])) {
    $response["message"] = "Sesión no válida.";
    echo json_encode($response);
    exit();
}
$usuario = $_SESSION["usuario"];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $direccion = trim($_POST['direccion'] ?? '');
    $fecha = trim($_POST['fecha'] ?? '');
    $hora = trim($_POST['hora'] ?? '');
    $cantidad = trim($_POST['cantidad'] ?? '');
    $comentarios = trim($_POST['comentarios'] ?? '');
    $id_colaborador = intval($_POST['id_colaborador'] ?? 0);
    $tipo_residuo = trim($_POST['tipo_residuo'] ?? '');

    if (empty($direccion) || empty($fecha) || empty($hora) || empty($cantidad) || empty($id_colaborador) || empty($tipo_residuo)) {
        $response["message"] = "Todos los campos son obligatorios.";
        echo json_encode($response);
        exit();
    }

    $conexion = new Conexion();
    $conexion->abrirConexion();
    $solicitudDAO = new SolicitudDAO();
    $exito = $solicitudDAO->crearSolicitudPuertaAPuerta(
        $conexion,
        $usuario->getIdUsuario(),
        $id_colaborador,
        $tipo_residuo,
        $direccion,
        $fecha,
        $hora,
        $cantidad,
        $comentarios
    );
    $conexion->cerrarConexion();
    if ($exito) {
        $response["success"] = true;
        $response["message"] = "¡Solicitud enviada correctamente!";
    } else {
        $response["message"] = "No se pudo registrar la solicitud. Intenta de nuevo.";
    }
    echo json_encode($response);
    exit();
}
$response["message"] = "Método no permitido.";
echo json_encode($response);
