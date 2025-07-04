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
    // Buscar el nombre del residuo por ID
    $residuoNombre = '';
    foreach ($residuos as $res) {
        if ($res->getId() == $residuoSeleccionado) {
            $residuoNombre = $res->getNombre();
            break;
        }
    }
    $colaboradores = $colaboradorDAO->buscarPorResiduo($residuoNombre);
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
<!-- Cambios aplicados en el <head> -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Solicitar Recolección Puerta a Puerta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background: linear-gradient(135deg, #f0fff4 0%, #e6ffe6 50%, #ccfc7b 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }

        .main-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin: auto;
            max-width: 700px;
        }

        .page-title {
            text-align: center;
            font-size: 2rem;
            color: #2d6e33;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .form-container {
            background: rgba(204, 252, 123, 0.1);
            border: 1px solid rgba(40, 167, 69, 0.2);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(40, 167, 69, 0.1);
        }

        .form-label {
            font-weight: 600;
            color: #2d6e33;
        }

        .form-select, .form-control {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(40, 167, 69, 0.3);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .form-select:focus, .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
            background: white;
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #ccfc7b 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #2d6e33 0%, #28a745 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
        }

        .alert {
            border-radius: 10px;
            padding: 1rem;
            font-weight: 500;
            margin-top: 1rem;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.15);
            color: #155724;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .alert-warning {
            background: rgba(255, 193, 7, 0.15);
            color: #856404;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.15);
            color: #721c24;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 1rem;
                width: 95%;
            }

            .form-container {
                padding: 1.5rem;
            }

            .page-title {
                font-size: 1.5rem;
            }
        }
    </style>

</head>

<body>
<?php include_once(__DIR__ . '/../navbarUser.php'); ?>
<div class="container">
    <div class="main-container mx-auto">
        <h2 class="page-title">Solicitar Recolección Puerta a Puerta de Residuos</h2>
        <form method="post" class="form-container">
        <div class="mb-3">
            <label for="residuo" class="form-label">Tipo de residuo:</label>
            <select class="form-select" id="residuo" name="tipo_residuo" required onchange="this.form.submit()">
                <option value="">Seleccione un residuo</option>
                <?php foreach ($residuos as $res): ?>
                    <option value="<?= htmlspecialchars($res->getId()) ?>" <?= $residuoSeleccionado == $res->getId() ? 'selected' : '' ?>>
                        <?= htmlspecialchars($res->getNombre()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php if ($residuoSeleccionado): ?>
            <?php if (count($colaboradores) > 0): ?>
                <div class="mb-3">
                    <label for="colaborador" class="form-label">Empresa recolectora:</label>
                    <select class="form-select mb-3" id="colaborador" name="colaborador" required>
                        <option value="">Seleccione una empresa</option>
                        <?php foreach ($colaboradores as $col): ?>
                            <option value="<?= htmlspecialchars($col['idColaborador']) ?>">
                                <?= htmlspecialchars($col['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" class="btn btn-success mt-2" onclick="redirigirFormulario()">Continuar</button>
                </div>
                <script>
                function redirigirFormulario() {
                    var residuo = document.getElementById('residuo').value;
                    var colaborador = document.getElementById('colaborador').value;
                    if (!residuo || !colaborador) {
                        alert('Debe seleccionar un residuo y una empresa.');
                        return;
                    }
                    window.location.href = 'formularioSolicitud.php?residuo=' + encodeURIComponent(residuo) + '&colaborador=' + encodeURIComponent(colaborador);
                }
                </script>
            <?php else: ?>
                <div class="alert alert-warning">No hay empresas colaboradoras que ofrezcan recolección a domicilio para este residuo.</div>
            <?php endif; ?>
        <?php endif; ?>
    </form>
    <div class="mt-3"><?php echo $feedback; ?></div>
</div>

</body>
</html>
