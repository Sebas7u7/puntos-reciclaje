<?php
require_once(__DIR__ . '/../../../logica/Solicitud.php');
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: /PuntosReciclaje/index.php");
    exit();
}
$idUsuario = $_SESSION["usuario"]->getIdUsuario();
$solicitudObj = new Solicitud();
$solicitudes = $solicitudObj->listarPorUsuario($idUsuario);

function tiempoRestante($fecha_programada) {
    $ahora = new DateTime();
    $programada = new DateTime($fecha_programada);
    if ($programada < $ahora) return 'Tiempo expirado';
    $interval = $ahora->diff($programada);
    return $interval->format('%d días, %h horas, %i minutos');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estado de mi Solicitud</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f0fff4 0%, #e6ffe6 50%, #ccfc7b 100%);
            font-family: 'Inter', sans-serif;
        }

        .titulo {
            margin-top: 40px;
            margin-bottom: 30px;
            color: #2d6e33;
            text-align: center;
            font-weight: 700;
        }

        .card-solicitud {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-left: 5px solid #28a745;
        }

        .badge {
            font-size: 0.9rem;
        }

        .detalle {
            color: #495057;
            font-size: 0.95rem;
        }

        .icon-title {
            color: #28a745;
            font-size: 1.4rem;
            margin-right: 8px;
        }

        .titulo-solicitud {
            color: #2d6e33;
            font-weight: 600;
        }

        /* Asegurar que los dropdowns del navbar funcionen correctamente */
        .navbar {
            position: relative;
            z-index: 1030;
        }

        .dropdown-menu {
            z-index: 1031;
        }
    </style>
</head>
<body>
<?php include_once(__DIR__ . '/../navbarUser.php'); ?>

<div class="container">
    <h2 class="titulo"><i class="bi bi-clock-history me-2"></i>Seguimiento de tus solicitudes</h2>

    <?php if (empty($solicitudes)): ?>
        <div class="alert alert-warning text-center">
            <i class="bi bi-info-circle me-2"></i> Aún no has realizado ninguna solicitud de recolección.
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($solicitudes as $solicitud): ?>
                <div class="col-md-6">
                    <div class="card-solicitud">
                        <h5 class="titulo-solicitud">
                            <i class="bi bi-trash3 icon-title"></i>
                            <?= htmlspecialchars($solicitud->getResiduo() ? $solicitud->getResiduo()->getNombre() : 'Residuo eliminado') ?>
                        </h5>
                        <p class="mb-1"><strong>Fecha de Solicitud:</strong> <?= htmlspecialchars($solicitud->getFechaSolicitud()) ?></p>
                        <p class="mb-1">
                            <strong>Estado:</strong>
                            <?php
                            switch ($solicitud->getEstado()) {
                                case 'pendiente':
                                    echo '<span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Pendiente</span>';
                                    break;
                                case 'programado':
                                    echo '<span class="badge bg-info text-dark"><i class="bi bi-calendar2-check me-1"></i>Programado</span>';
                                    break;
                                case 'completado':
                                    echo '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Completado</span>';
                                    break;
                                default:
                                    echo htmlspecialchars($solicitud->getEstado());
                            }
                            ?>
                        </p>
                        <p class="detalle mt-3">
                            <?php
                            if ($solicitud->getEstado() === 'pendiente') {
                                echo '<i class="bi bi-info-circle me-1"></i>El colaborador aún no ha revisado la solicitud.';
                            } elseif ($solicitud->getEstado() === 'programado') {
                                echo '<i class="bi bi-clock me-1"></i>Tiempo restante: ' . tiempoRestante($solicitud->getFechaProgramada());
                            } elseif ($solicitud->getEstado() === 'completado') {
                                echo nl2br(htmlspecialchars($solicitud->getDescripcionProceso()));
                            } else {
                                echo '-';
                            }
                            ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Bootstrap JavaScript para dropdowns -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
