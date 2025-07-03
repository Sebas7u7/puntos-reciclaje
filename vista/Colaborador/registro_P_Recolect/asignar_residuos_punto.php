<?php
require_once(__DIR__ . '/../../../logica/Colaborador.php');
session_start();
if (!isset($_SESSION["colaborador"])) {
    header("Location: /PuntosReciclaje/index.php");
    exit();
}
require_once(__DIR__ . '/../../../logica/Punto_recoleccion.php');
require_once(__DIR__ . '/../../../logica/Residuo.php');
require_once(__DIR__ . '/../../../persistencia/Conexion.php');
require_once(__DIR__ . '/../../../persistencia/PuntoResiduoDAO.php');

// Procesar formulario
if (isset($_POST['asignar_residuos'])) {
    $idPunto = intval($_POST['punto']);
    $residuosSeleccionados = isset($_POST['residuos']) ? $_POST['residuos'] : [];
    $conexion = new Conexion();
    $conexion->abrirConexion();
    // Eliminar residuos actuales
    $conexion->ejecutarConsultaDirecta("DELETE FROM punto_residuo WHERE Punto_Recoleccion_idPunto_Recoleccion = $idPunto");
    // Insertar los nuevos
    foreach ($residuosSeleccionados as $idResiduo) {
        $sql = "INSERT INTO punto_residuo (Residuo_idResiduo, Punto_Recoleccion_idPunto_Recoleccion) VALUES ($idResiduo, $idPunto)";
        $conexion->ejecutarConsultaDirecta($sql);
    }
    $conexion->cerrarConexion();
    $_SESSION['message_type'] = 'success';
    $_SESSION['message'] = 'Residuos asignados correctamente al punto.';
    header("Location: asignar_residuos_punto.php");
    exit();
}

$puntoObj = new Punto_recoleccion();
$puntos = $puntoObj->listar();
$residuoObj = new Residuo();
$residuos = $residuoObj->listar();

function residuosDelPunto($idPunto) {
    $conexion = new Conexion();
    $conexion->abrirConexion();
    $dao = new PuntoResiduoDAO();
    $conexion->ejecutarConsulta($dao->obtenerResiduosPorPunto($idPunto));
    $res = [];
    while ($row = $conexion->siguienteRegistro()) {
        $res[] = $row[0];
    }
    $conexion->cerrarConexion();
    return $res;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Asignar Residuos a Punto de Recolección</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include(__DIR__ . '/../navbarColaborador.php'); ?>
<div class="container mt-4">
    <h2 class="mb-4">Asignar residuos a un punto de recolección</h2>
    <?php
    if (isset($_SESSION['message'])) {
        echo '<div class="alert alert-' . $_SESSION['message_type'] . '">' . $_SESSION['message'] . '</div>';
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
    ?>
    <form method="POST">
        <div class="mb-3">
            <label for="punto" class="form-label">Selecciona el punto de recolección:</label>
            <select class="form-select" id="punto" name="punto" required onchange="this.form.submit()">
                <option value="">-- Selecciona un punto --</option>
                <?php foreach ($puntos as $p): ?>
                    <option value="<?php echo $p->getIdPuntoRecoleccion(); ?>" <?php if(isset($_POST['punto']) && $_POST['punto']==$p->getIdPuntoRecoleccion()) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($p->getNombre()); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php
        $residuosMarcados = [];
        if (isset($_POST['punto']) && $_POST['punto']) {
            $residuosMarcados = residuosDelPunto($_POST['punto']);
        }
        ?>
        <div class="mb-3">
            <label class="form-label">Residuos que recibe este punto:</label>
            <div class="row">
                <?php foreach ($residuos as $residuo): ?>
                    <div class="col-6 col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="residuos[]" value="<?php echo $residuo->getId(); ?>" id="residuo_<?php echo $residuo->getId(); ?>" <?php echo in_array($residuo->getNombre(), $residuosMarcados) ? 'checked' : ''; ?> <?php echo !isset($_POST['punto']) ? 'disabled' : ''; ?>>
                            <label class="form-check-label" for="residuo_<?php echo $residuo->getId(); ?>">
                                <?php echo htmlspecialchars($residuo->getNombre()); ?>
                            </label>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <button type="submit" name="asignar_residuos" class="btn btn-success" <?php echo !isset($_POST['punto']) ? 'disabled' : ''; ?>>Guardar residuos</button>
    </form>
</div>
</body>
</html>
