

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        :root {
            --primary-green: #2d6e33;
            --secondary-green: #28a745;
            --accent-green: #ccfc7b;
            --glass-bg: rgba(255, 255, 255, 0.15);
            --glass-border: rgba(255, 255, 255, 0.2);
            --shadow-primary: 0 8px 32px rgba(45, 110, 51, 0.3);
            --shadow-card: 0 10px 40px rgba(0, 0, 0, 0.1);
            --gradient-primary: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 50%, var(--accent-green) 100%);
        }

        body {
            background: linear-gradient(135deg, #f0fff4 0%, #e6ffe6 50%, #ccfc7b 100%);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        .main-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 24px;
            padding: 3rem;
            box-shadow: var(--shadow-card);
            border: 1px solid var(--glass-border);
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        .page-title {
            color: var(--primary-green);
            font-weight: 700;
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--primary-green);
        }

        .form-control {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid var(--accent-green);
            border-radius: 16px;
            padding: 1rem;
            font-size: 1rem;
            font-weight: 500;
            color: var(--primary-green);
        }

        .form-control:focus {
            border-color: var(--secondary-green);
            box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.2);
        }

        .form-control:disabled {
            background: rgba(204, 252, 123, 0.2);
        }

        .btn-submit {
            background: var(--gradient-primary);
            border: none;
            border-radius: 16px;
            padding: 1rem 2.5rem;
            font-weight: 700;
            color: white;
            text-transform: uppercase;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #245c2a 0%, #2d6e33 50%, #ccfc7b 100%);
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(40, 167, 69, 0.4);
        }

        .alert {
            border-radius: 16px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .summary-card {
            background: linear-gradient(135deg, rgba(204, 252, 123, 0.2) 0%, rgba(40, 167, 69, 0.1) 100%);
            border: 1px solid rgba(40, 167, 69, 0.2);
            border-radius: 16px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
<?php include_once(__DIR__ . '/../navbarUser.php'); ?>
<div class="container">
    <div class="main-container">
        <h2 class="page-title">Completar Solicitud de Recolección</h2>
        <form method="post">
            <input type="hidden" name="residuo" value="<?= htmlspecialchars($residuo) ?>">
            <input type="hidden" name="colaborador" value="<?= htmlspecialchars($colaborador) ?>">

            <div class="summary-card">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Usuario:</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($usuario ? $usuario->getNombre() : '') ?>" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Residuo:</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($residuoNombre) ?>" disabled>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Empresa:</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($colaboradorNombre) ?>" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Estado:</label>
                        <input type="text" class="form-control" value="En espera" disabled>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label" for="direccion">Dirección de recolección:</label>
                <input type="text" class="form-control" id="direccion" name="direccion" required placeholder="Ej: Calle 123 #45-67, Barrio ...">
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="fecha">Fecha preferida:</label>
                    <input type="date" class="form-control" id="fecha" name="fecha" required min="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="hora">Hora preferida:</label>
                    <input type="time" class="form-control" id="hora" name="hora" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="cantidad">Cantidad de residuos:</label>
                <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" required placeholder="Ej: 2">
            </div>
            <div class="mb-3">
                <label class="form-label" for="comentarios">Comentarios adicionales:</label>
                <textarea class="form-control" id="comentarios" name="comentarios" rows="2" placeholder="Opcional"></textarea>
            </div>
            <button type="submit" name="enviar_solicitud" class="btn-submit">
                <i class="bi bi-send-fill me-2"></i>Solicitar Recolección
            </button>
        </form>

        <?php if ($feedback): ?>
            <div class="alert alert-dismissible fade show mt-3">
                <?= $feedback ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<script>
    document.querySelector('form').addEventListener('submit', function(e) {
        const fecha = document.getElementById('fecha').value;
        const hora = document.getElementById('hora').value;
        if (!fecha || !hora) return;
        const fechaHora = new Date(fecha + 'T' + hora);
        const ahora = new Date();
        if (fechaHora <= ahora) {
            e.preventDefault();
            alert('La fecha y hora preferida deben ser posteriores a la actual.');
        }
    });
</script>
</body>
</html>

