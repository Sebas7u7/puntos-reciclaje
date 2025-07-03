<?php
// Vista: Formulario para solicitar recolección puerta a puerta de residuo electrónico
// Este archivo debe ir en vista/Usuario/solicitarRecoleccion.php

require_once(__DIR__ . '/../../../logica/Usuario.php');
require_once(__DIR__ . '/../../../logica/Residuo.php');
require_once(__DIR__ . '/../../../persistencia/ColaboradorDAO.php');
$usuario = isset($_SESSION["usuario"]) ? $_SESSION["usuario"] : null;

$residuoObj = new Residuo();
$residuos = $residuoObj->listar();

$colaboradores = [];
$residuoSeleccionado = isset($_POST['tipo_residuo']) ? $_POST['tipo_residuo'] : '';
if ($residuoSeleccionado) {
    $colaboradorDAO = new ColaboradorDAO();
    $colaboradores = $colaboradorDAO->buscarPorResiduo($residuoSeleccionado);
}

$feedback = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar_solicitud'])) {
    require_once(__DIR__ . '/../../../persistencia/SolicitudDAO.php');
    require_once(__DIR__ . '/../../../persistencia/Conexion.php');
    $idUsuario = $usuario ? $usuario->getIdUsuario() : null;
    $idColaborador = isset($_POST['id_colaborador']) ? $_POST['id_colaborador'] : null;
    $tipoResiduo = isset($_POST['tipo_residuo']) ? $_POST['tipo_residuo'] : null;
    $direccion = isset($_POST['direccion']) ? $_POST['direccion'] : '';
    $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';
    $hora = isset($_POST['hora']) ? $_POST['hora'] : '';
    $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;
    $comentarios = isset($_POST['comentarios']) ? $_POST['comentarios'] : '';

    if ($idUsuario && $idColaborador && $tipoResiduo && $direccion && $fecha && $hora && $cantidad) {
        $conexion = new Conexion();
        $conexion->abrirConexion();
        $solicitudDAO = new SolicitudDAO();
        $ok = $solicitudDAO->crearSolicitudPuertaAPuerta($conexion, $idUsuario, $idColaborador, $tipoResiduo, $direccion, $fecha, $hora, $cantidad, $comentarios);
        $conexion->cerrarConexion();
        if ($ok) {
            $feedback = '<div class="alert alert-success">Solicitud enviada correctamente.</div>';
        } else {
            $feedback = '<div class="alert alert-danger">Error al guardar la solicitud. Verifique los datos.</div>';
        }
    } else {
        $feedback = '<div class="alert alert-danger">Faltan datos obligatorios.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Solicitar Recolección Puerta a Puerta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include_once(__DIR__ . '/../navbarUser.php'); ?>
<div class="container mt-5">
    <h2 class="mb-4">Solicitar Recolección Puerta a Puerta de Residuos</h2>
    <form method="post" class="border p-4 rounded bg-light">
        <div class="mb-3">
            <label for="residuo" class="form-label">Tipo de residuo:</label>
            <select class="form-select" id="residuo" name="tipo_residuo" required onchange="this.form.submit()">
                <option value="">Seleccione un residuo</option>
                <?php foreach ($residuos as $res): ?>
                    <option value="<?= htmlspecialchars($res->getNombre()) ?>" <?= $residuoSeleccionado == $res->getNombre() ? 'selected' : '' ?>>
                        <?= htmlspecialchars($res->getNombre()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php if ($residuoSeleccionado): ?>
            <?php if (count($colaboradores) > 0): ?>
                <div class="mb-3">
                    <label for="colaborador" class="form-label">Empresa recolectora:</label>
                    <select class="form-select" id="colaborador" name="id_colaborador" required>
                        <option value="">Seleccione una empresa</option>
                        <?php foreach ($colaboradores as $col): ?>
                            <option value="<?= htmlspecialchars($col['idColaborador']) ?>">
                                <?= htmlspecialchars($col['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección de recolección:</label>
                        <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo htmlspecialchars($usuario->getDireccion() ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha preferida:</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" required>
                    </div>
                    <div class="mb-3">
                        <label for="hora" class="form-label">Hora preferida:</label>
                        <input type="time" class="form-control" id="hora" name="hora" required>
                    </div>
                    <div class="mb-3">
                        <label for="cantidad" class="form-label">Cantidad de residuos:</label>
                        <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="comentarios" class="form-label">Comentarios adicionales:</label>
                        <textarea class="form-control" id="comentarios" name="comentarios" rows="2"></textarea>
                    </div>
                    <button type="submit" name="enviar_solicitud" class="btn btn-success">Solicitar Recolección</button>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">No hay empresas colaboradoras que ofrezcan recolección a domicilio para este residuo.</div>
            <?php endif; ?>
        <?php endif; ?>
    </form>
    <div class="mt-3"><?php echo $feedback; ?></div>
</div>

</body>
</html>
