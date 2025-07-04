<?php
// Devuelve la dirección actual del usuario desde la base de datos (por idUsuario)
require_once(__DIR__ . '/../../../persistencia/UsuarioDAO.php');
require_once(__DIR__ . '/../../../persistencia/Conexion.php');
require_once(__DIR__ . '/../../../logica/Usuario.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$usuario = isset($_SESSION["usuario"]) ? $_SESSION["usuario"] : null;
$direccion = '';
if ($usuario) {
    $conexion = new Conexion();
    $conexion->abrirConexion();
    $usuarioDAO = new UsuarioDAO();
    $datos = $usuarioDAO->consultarPorId($conexion, $usuario->getIdUsuario());
    if ($datos && isset($datos['direccion'])) {
        $direccion = $datos['direccion'];
    }
    $conexion->cerrarConexion();
}
echo htmlspecialchars($direccion ?? '');
