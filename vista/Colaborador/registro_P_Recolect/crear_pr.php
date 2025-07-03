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

    // Registrar el punto
    $punto->registrar(
        $nombre,
        $direccion,
        $latitud,
        $longitud,
        $estado,
        $_SESSION["colaborador"]->getIdColaborador()
    );

    // Obtener el id del punto recién creado
    require_once (__DIR__ . '/../../../persistencia/Conexion.php');
    $conexion = new Conexion();
    $conexion->abrirConexion();
    $idPunto = $conexion->obtenerLlaveAutonumerica();
    $conexion->cerrarConexion();

    // Guardar residuos seleccionados
    if (!empty($_POST['residuos'])) {
        require_once (__DIR__ . '/../../../persistencia/PuntoResiduoDAO.php');
        $conexion = new Conexion();
        $conexion->abrirConexion();
        foreach ($_POST['residuos'] as $idResiduo) {
            $sql = "INSERT INTO punto_residuo (Residuo_idResiduo, Punto_Recoleccion_idPunto_Recoleccion) VALUES ($idResiduo, $idPunto)";
            $conexion->ejecutarConsultaDirecta($sql);
        }
        $conexion->cerrarConexion();
    }

    $_SESSION['message_type'] = 'success';
    $_SESSION['message'] = 'punto recoleccion registrado exitosamente.';
    header("Location: /puntos-reciclaje/vista/Colaborador/registro_P_Recolect/registrarP_Recolect.php");
    exit();
}
?>