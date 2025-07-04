<?php
require_once(__DIR__ . '/../../../logica/Colaborador.php');
session_start();
if (!isset($_SESSION["colaborador"])) {
    header("Location: /PuntosReciclaje/index.php");
    exit();
}

require_once(__DIR__ . '/../../../logica/Punto_recoleccion.php');
require_once(__DIR__ . '/../../../logica/AsignacionResiduoPunto.php');


// Procesar formulario
if (isset($_POST['asignar_residuos'])) {
    $idPunto = intval($_POST['punto']);
    $residuosSeleccionados = isset($_POST['residuos']) ? $_POST['residuos'] : [];
    AsignacionResiduoPunto::asignarResiduosAPunto($idPunto, $residuosSeleccionados);
    $_SESSION['message_type'] = 'success';
    $_SESSION['message'] = 'Residuos asignados correctamente al punto.';
    header("Location: asignar_residuos_punto.php");
    exit();
}

// Procesar eliminación de punto
if (isset($_POST['eliminar_punto']) && isset($_POST['punto'])) {
    require_once(__DIR__ . '/../../../logica/Punto_recoleccion.php');
    $puntoObj = new Punto_recoleccion();
    $idPunto = intval($_POST['punto']);
    $puntoObj->eliminar($idPunto);
    $_SESSION['message_type'] = 'success';
    $_SESSION['message'] = 'Punto eliminado correctamente.';
    header("Location: asignar_residuos_punto.php");
    exit();
}

$puntoObj = new Punto_recoleccion();
$puntos = [];
$colaborador = $_SESSION["colaborador"];
if ($colaborador && method_exists($colaborador, 'getIdColaborador')) {
    // Solo mostrar puntos creados por el colaborador actual
    $puntos = $puntoObj->listarPorColaborador($colaborador->getIdColaborador());
}
$residuos = [];
if ($colaborador && method_exists($colaborador, 'getIdColaborador')) {
    $residuos = AsignacionResiduoPunto::obtenerResiduosColaborador($colaborador->getIdColaborador());
}


function residuosDelPunto($idPunto) {
    return AsignacionResiduoPunto::obtenerResiduosDePunto($idPunto);
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
            <div class="input-group">
                <select class="form-select" id="punto" name="punto" required onchange="this.form.submit()">
                    <option value="">-- Selecciona un punto --</option>
                    <?php foreach ($puntos as $p): ?>
                        <option value="<?php echo $p->getIdPuntoRecoleccion(); ?>" <?php if(isset($_POST['punto']) && $_POST['punto']==$p->getIdPuntoRecoleccion()) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($p->getNombre()); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="eliminar_punto" class="btn btn-danger ms-2" onclick="return confirm('¿Estás seguro de eliminar este punto?');" <?php echo !isset($_POST['punto']) || !$_POST['punto'] ? 'disabled' : ''; ?>>Eliminar punto</button>
            </div>
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
