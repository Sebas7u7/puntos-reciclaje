<?php
require_once(__DIR__ . '/../../../logica/Solicitud.php');
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: /PuntosReciclaje/index.php");
    exit();
}
$idUsuario = $_SESSION["usuario"]->getIdUsuario();
$solicitudObj = new Solicitud();
$solicitudes = $solicitudObj->listarPorUsuario($idUsuario); // Debes implementar este método si no existe
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<?php include_once(__DIR__ . '/../navbarUser.php'); ?>
<div class="container mt-5">
    <h2 class="mb-4">Seguimiento de tu solicitud de recolección</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Residuo</th>
                <th>Fecha Solicitud</th>
                <th>Estado</th>
                <th>Detalle</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($solicitudes as $solicitud): ?>
            <tr>
                <td><?= htmlspecialchars($solicitud->getId()) ?></td>
                <td><?= htmlspecialchars($solicitud->getResiduo() ? $solicitud->getResiduo()->getNombre() : 'Residuo eliminado') ?></td>
                <td><?= htmlspecialchars($solicitud->getFechaSolicitud()) ?></td>
                <td>
                    <?php
                    if ($solicitud->getEstado() == 'pendiente') {
                        echo '<span class="badge bg-warning text-dark">Pendiente</span>';
                    } elseif ($solicitud->getEstado() == 'programado') {
                        echo '<span class="badge bg-info text-dark">Programado</span>';
                    } elseif ($solicitud->getEstado() == 'completado') {
                        echo '<span class="badge bg-success">Completado</span>';
                    } else {
                        echo htmlspecialchars($solicitud->getEstado());
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if ($solicitud->getEstado() == 'pendiente') {
                        echo 'El colaborador no ha revisado la solicitud.';
                    } elseif ($solicitud->getEstado() == 'programado') {
                        echo 'Tiempo restante: ' . tiempoRestante($solicitud->getFechaProgramada());
                    } elseif ($solicitud->getEstado() == 'completado') {
                        echo nl2br(htmlspecialchars($solicitud->getDescripcionProceso()));
                    } else {
                        echo '-';
                    }
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
