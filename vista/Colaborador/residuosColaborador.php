<?php
// Cabeceras anti-caché para evitar acceso tras logout o retroceso
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

require_once(__DIR__ . '/navbarColaborador.php');
require_once(__DIR__ . '/../../persistencia/Conexion.php');
require_once(__DIR__ . '/../../persistencia/ResiduoDAO.php');
require_once(__DIR__ . '/../../persistencia/ColaboradorDAO.php');

if (!isset($_SESSION["colaborador"])) {
    header("Location: /PuntosReciclaje/index.php");
    exit();
}
$colaborador = $_SESSION["colaborador"];

$mensaje = "";
$tipo_mensaje = "";

$conexion = new Conexion();
$conexion->abrirConexion();
$residuoDAO = new ResiduoDAO();
$colaboradorDAO = new ColaboradorDAO();

$residuos = $residuoDAO->listarTodos($conexion);
$residuosColaborador = $colaboradorDAO->obtenerResiduosColaborador($conexion, $colaborador->getIdColaborador());
$observacionesActuales = $colaboradorDAO->obtenerObservacionesResiduos($conexion, $colaborador->getIdColaborador());

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_residuos'])) {
    $seleccionados = isset($_POST['residuos']) ? $_POST['residuos'] : [];
    $observaciones = isset($_POST['observaciones']) ? trim($_POST['observaciones']) : '';
    if (empty($seleccionados)) {
        $mensaje = "Debes seleccionar al menos un tipo de residuo e-waste.";
        $tipo_mensaje = "danger";
    } else {
        $colaboradorDAO->actualizarResiduosColaborador($conexion, $colaborador->getIdColaborador(), $seleccionados, $observaciones);
        $mensaje = "Información de residuos actualizada correctamente.";
        $tipo_mensaje = "success";
        $residuosColaborador = $colaboradorDAO->obtenerResiduosColaborador($conexion, $colaborador->getIdColaborador());
        $observacionesActuales = $observaciones;
    }
}
$conexion->cerrarConexion();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Residuos gestionados - Puntos de Reciclaje</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to top, #ccfc7b 40%, white);
            font-family: 'Quicksand', sans-serif;
            min-height: 100vh;
        }

        .residuo-form-card {
            margin-top: 50px;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        h2.title {
            color: #014421;
            font-weight: bold;
            text-align: center;
            margin-bottom: 25px;
        }

        .form-label {
            font-weight: bold;
            color: #014421;
        }

        .btn-custom {
            background-color: #ccfc7b;
            color: white;
            border: none;
        }

        .btn-custom:hover {
            background-color: white;
            color: #ccfc7b;
            border: 1px solid #ccfc7b;
        }

        .badge.bg-info {
            background-color: #ccfc7b !important;
            color: #000 !important;
        }

        .alert-success {
            background-color: #d1fae5;
            color: #014421;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #842029;
        }

        select.form-select,
        textarea.form-control,
        input.form-control {
            border-radius: 10px;
            border-color: #ccfc7b;
        }

        /* Estilo del contenedor */
        #lista_residuos {
            background-color: #ffffff;
            border: none !important;
        }

        /* Tarjeta verde clara con letra negra */
        .card.bg-light-green {
            background-color: #ccfc7b !important;
            color: #000 !important;
            border: none !important;
        }

        /* Asegura que texto y descripción dentro de la tarjeta sean negros */
        .card.bg-light-green strong,
        .card.bg-light-green small {
            color: #000 !important;
        }

        /* Checkbox verde oscuro al estar seleccionado */
        .card.bg-light-green input[type="checkbox"] {
            accent-color: #014421;
        }

        .btn-dark-green {
            background-color: #014421 !important;
            color: white !important;
            border: none !important;
        }

        .btn-dark-green:hover {
            background-color: #012d1a !important;
            color: #ccfc7b !important;
        }

        .header-title {
            font-weight: bold;
            color: #014421;
            /* verde oscuro */
            font-size: 32px;
        }
    </style>
</head>

<body>
    <div class="container text-center mt-4">
        <h1 class="mt-3 header-title">Panel del Colaborador</h1>
        <hr class="mb-4">
    </div>

    <div class="container">
        <div class="row justify-content-center">

            <div class="col-md-10 col-lg-9">

                <div class="residuo-form-card">

                    <h2 class="title">Tipos de residuos gestionados</h2>

                    <?php if ($mensaje): ?>
                        <div class="alert alert-<?php echo htmlspecialchars($tipo_mensaje); ?> alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($mensaje); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="residuosColaborador.php">
                        <div class="mb-3">
                            <label for="filtro_categoria" class="form-label">Filtrar por categoría:</label>
                            <select id="filtro_categoria" class="form-select mb-3" onchange="filtrarPorCategoria()">
                                <option value="">Todas</option>
                                <?php
                                $categorias = array_unique(array_map(fn($r) => $r['categoria'], $residuos));
                                sort($categorias);
                                foreach ($categorias as $cat): ?>
                                    <option value="<?php echo htmlspecialchars($cat); ?>"><?php echo htmlspecialchars($cat); ?></option>
                                <?php endforeach; ?>
                            </select>

                            <label class="form-label">Selecciona los tipos de residuos electrónicos que gestionas <span class="text-danger">*</span>:</label>
                            <div class="overflow-auto bg-white rounded p-2" id="lista_residuos" style="max-height: 400px; min-height: 120px;">
                                <div class="row flex-nowrap flex-md-wrap g-2">
                                    <?php foreach ($residuos as $residuo): ?>
                                        <div class="col-12 col-md-6 mb-2 residuo-item" data-categoria="<?php echo htmlspecialchars($residuo['categoria']); ?>" style="min-width: 320px;">
                                            <label class="form-check-label card bg-light-green p-3 h-100 w-100 position-relative">
                                                <input class="form-check-input position-absolute top-0 start-0 m-2" type="checkbox" name="residuos[]"
                                                    id="residuo_<?php echo $residuo['idResiduo']; ?>"
                                                    value="<?php echo $residuo['idResiduo']; ?>"
                                                    <?php echo in_array($residuo['idResiduo'], $residuosColaborador) ? 'checked' : ''; ?>>
                                                <div class="ms-4">
                                                    <strong><?php echo htmlspecialchars($residuo['nombre']); ?></strong>
                                                    <small class="d-block mt-1"><?php echo nl2br(htmlspecialchars($residuo['descripcion'])); ?></small>
                                                </div>
                                                <span class="badge bg-light text-dark position-absolute top-0 end-0 m-2 text-wrap"
                                                    style="max-width: 120px; white-space: normal;">
                                                    <?php echo htmlspecialchars($residuo['categoria']); ?>
                                                </span>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                        </div>

                        <div class="mb-3">
                            <label for="observaciones" class="form-label">Observaciones / Notas (opcional):</label>
                            <textarea class="form-control" id="observaciones" name="observaciones" rows="3"><?php echo htmlspecialchars($observacionesActuales); ?></textarea>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" name="guardar_residuos" class="btn btn-custom btn-lg">Guardar</button>
                            <a href="indexColaborador.php" class="btn btn-success">Volver</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function filtrarPorCategoria() {
            const cat = document.getElementById('filtro_categoria').value;
            const items = document.querySelectorAll('.residuo-item');
            items.forEach(item => {
                item.style.display = (!cat || item.getAttribute('data-categoria') === cat) ? '' : 'none';
            });
        }
    </script>
</body>

</html>