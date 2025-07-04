

<?php
// Debug y robustez: mostrar errores y asegurar sesión
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__DIR__ . '/../../../logica/Usuario.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$usuario = isset($_SESSION["usuario"]) ? $_SESSION["usuario"] : null;

$residuo = isset($_GET['residuo']) ? $_GET['residuo'] : (isset($_POST['residuo']) ? $_POST['residuo'] : '');
$colaborador = isset($_GET['colaborador']) ? $_GET['colaborador'] : (isset($_POST['colaborador']) ? $_POST['colaborador'] : '');

// Debug: mostrar valores recibidos (eliminado para producción)

// Obtener nombre de residuo y colaborador para mostrar resumen
$residuoNombre = '';
$colaboradorNombre = '';
if ($residuo) {
    require_once(__DIR__ . '/../../../logica/Residuo.php');
    $residuoObj = new Residuo();
    $residuoData = $residuoObj->buscarPorId($residuo);
    if ($residuoData) {
        $residuoNombre = $residuoData->getNombre();
    }
}
if ($colaborador) {
    require_once(__DIR__ . '/../../../persistencia/ColaboradorDAO.php');
    $colaboradorDAO = new ColaboradorDAO();
    $colaboradorData = $colaboradorDAO->buscarPorId($colaborador);
    if ($colaboradorData) {
        $colaboradorNombre = $colaboradorData['nombre'];
    }
}
$feedback = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar_solicitud'])) {
    require_once(__DIR__ . '/../../../persistencia/SolicitudDAO.php');
    require_once(__DIR__ . '/../../../persistencia/Conexion.php');
    $idUsuario = $usuario ? $usuario->getIdUsuario() : null;
    $idColaborador = isset($_POST['colaborador']) ? $_POST['colaborador'] : $colaborador;
    $idResiduo = isset($_POST['residuo']) ? $_POST['residuo'] : $residuo;
    $direccion = isset($_POST['direccion']) ? $_POST['direccion'] : '';
    $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';
    $hora = isset($_POST['hora']) ? $_POST['hora'] : '';
    $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;
    $comentarios = isset($_POST['comentarios']) ? $_POST['comentarios'] : '';

    // Validar que los campos del formulario estén llenos y la fecha/hora sean válidas
    if ($direccion && $fecha && $hora && $cantidad) {
        $fechaHoraSolicitud = strtotime($fecha . ' ' . $hora);
        $ahora = time();
        if ($fechaHoraSolicitud <= $ahora) {
            $feedback = '<div class="alert alert-danger">La fecha y hora preferida deben ser posteriores a la fecha y hora actual.</div>';
        } else {
            $conexion = new Conexion();
            $conexion->abrirConexion();
            $solicitudDAO = new SolicitudDAO();
            $ok = $solicitudDAO->crearSolicitudPuertaAPuerta($conexion, $idUsuario, $idColaborador, $idResiduo, $direccion, $fecha, $hora, $cantidad, $comentarios);
            $conexion->cerrarConexion();
            if ($ok) {
                $feedback = '<div class="alert alert-success">Solicitud enviada correctamente.</div>';
            } else {
                $feedback = '<div class="alert alert-danger">Error al guardar la solicitud. Verifique los datos.</div>';
            }
        }
    } else {
        $feedback = '<div class="alert alert-danger">Por favor complete todos los campos.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Completar Solicitud de Recolección</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include_once(__DIR__ . '/../navbarUser.php'); ?>
<div class="container mt-5">
    <h2 class="mb-4">Completar Solicitud de Recolección</h2>
    <form method="post" class="border p-4 rounded bg-light">
        <input type="hidden" name="residuo" value="<?php echo htmlspecialchars($residuo); ?>">
        <input type="hidden" name="colaborador" value="<?php echo htmlspecialchars($colaborador); ?>">

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Usuario:</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($usuario ? $usuario->getNombre() : ''); ?>" disabled>
            </div>
            <div class="col-md-6">
                <label class="form-label">Residuo:</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($residuoNombre); ?>" disabled>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Empresa:</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($colaboradorNombre); ?>" disabled>
            </div>
            <div class="col-md-6">
                <label class="form-label">Estado:</label>
                <input type="text" class="form-control" value="En espera" disabled>
            </div>
        </div>
        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección de recolección:</label>
            <input type="text" class="form-control" id="direccion" name="direccion" value="<?php include(__DIR__ . '/direccion_usuario.php'); ?>" required placeholder="Ej: Calle 123 #45-67, Barrio ...">
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="fecha" class="form-label">Fecha preferida:</label>
                <input type="date" class="form-control" id="fecha" name="fecha" required min="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label for="hora" class="form-label">Hora preferida:</label>
                <input type="time" class="form-control" id="hora" name="hora" required>
            </div>
        </div>
        <div class="mb-3">
            <label for="cantidad" class="form-label">Cantidad de residuos:</label>
            <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" required placeholder="Ej: 2">
        </div>
        <div class="mb-3">
            <label for="comentarios" class="form-label">Comentarios adicionales:</label>
            <textarea class="form-control" id="comentarios" name="comentarios" rows="2" placeholder="Opcional"></textarea>
        </div>
        <button type="submit" name="enviar_solicitud" class="btn btn-success">Solicitar Recolección</button>
    </form>
    <div class="mt-3"><?php echo $feedback; ?></div>
<script>
// Validación frontend: fecha/hora posterior a la actual
document.querySelector('form').addEventListener('submit', function(e) {
    const fecha = document.getElementById('fecha').value;
    const hora = document.getElementById('hora').value;
    if (!fecha || !hora) return;
    const fechaHora = new Date(fecha + 'T' + hora);
    const ahora = new Date();
    if (fechaHora <= ahora) {
        e.preventDefault();
        alert('La fecha y hora preferida deben ser posteriores a la fecha y hora actual.');
    }
});
</script>
</div>
</body>
</html>
