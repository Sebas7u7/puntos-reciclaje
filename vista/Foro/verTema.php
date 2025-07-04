<?php
require_once(__DIR__ . '/../../persistencia/ForoTemaDAO.php');
require_once(__DIR__ . '/../../persistencia/ForoRespuestaDAO.php');
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: /puntos-reciclaje/index.php");
    exit();
}
$idTema = $_GET['id'] ?? null;
if (!$idTema) {
    echo '<div class="alert alert-danger">Tema no especificado.</div>';
    exit();
}
$temaDAO = new ForoTemaDAO();
$temas = $temaDAO->listar();
$tema = null;
foreach ($temas as $t) { if ($t->getIdTema() == $idTema) { $tema = $t; break; } }
if (!$tema) {
    echo '<div class="alert alert-danger">Tema no encontrado.</div>';
    exit();
}
$respuestaDAO = new ForoRespuestaDAO();
$respuestas = $respuestaDAO->listarPorTema($idTema);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contenido = $_POST['contenido'] ?? '';
    $idUsuario = $_SESSION['usuario']->getIdUsuario();
    $idRespuestaPadre = isset($_POST['respuesta_padre']) && $_POST['respuesta_padre'] !== '' ? $_POST['respuesta_padre'] : null;
    $respuestaDAO->crear($idTema, $idUsuario, $contenido, $idRespuestaPadre);
    header('Location: verTema.php?id=' . $idTema);
    exit();
}
function mostrarRespuestas($respuestas, $padre = null, $nivel = 0) {
    foreach ($respuestas as $r) {
        if ($r->getRespuestaPadre() == $padre) {
            echo '<div class="ms-' . ($nivel * 4) . ' mb-3">';
            echo '<div class="card"><div class="card-body">';
            echo '<div class="d-flex justify-content-between align-items-center">';
            echo '<strong>' . ($r->getUsuario() ? htmlspecialchars($r->getUsuario()->getNombre()) : 'Usuario') . '</strong>';
            echo '<small class="text-muted">' . htmlspecialchars($r->getFecha()) . '</small>';
            echo '</div>';
            echo '<p>' . nl2br(htmlspecialchars($r->getContenido())) . '</p>';
            echo '<button class="btn btn-link btn-sm" onclick="responder(' . $r->getIdRespuesta() . ')"><i class="bi bi-reply"></i> Responder</button>';
            echo '</div></div>';
            mostrarRespuestas($respuestas, $r->getIdRespuesta(), $nivel + 1);
            echo '</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($tema->getTitulo()) ?> - Foro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
<?php include_once(__DIR__ . '/../Usuario/navbarUser.php'); ?>
<div class="container mt-5">
    <a href="index.php" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i> Volver al foro</a>
    <div class="card mb-4">
        <div class="card-body">
            <h3><?= htmlspecialchars($tema->getTitulo()) ?></h3>
            <p class="text-muted mb-1">Por <?= $tema->getUsuario() ? htmlspecialchars($tema->getUsuario()->getNombre()) : 'Usuario' ?> | <?= htmlspecialchars($tema->getFechaCreacion()) ?></p>
            <?php if ($tema->getCategoria()): ?>
                <span class="badge bg-success"> <?= htmlspecialchars($tema->getCategoria()->getNombre()) ?> </span>
            <?php endif; ?>
            <hr>
            <p><?= nl2br(htmlspecialchars($tema->getDescripcion())) ?></p>
        </div>
    </div>
    <h5>Respuestas</h5>
    <div id="respuestas">
        <?php mostrarRespuestas($respuestas); ?>
    </div>
    <div class="card mt-4">
        <div class="card-body">
            <h6>Responder</h6>
            <form method="post">
                <input type="hidden" name="respuesta_padre" id="respuesta_padre" value="">
                <div class="mb-3">
                    <textarea class="form-control" name="contenido" rows="3" required placeholder="Escribe tu respuesta..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> Publicar respuesta</button>
            </form>
        </div>
    </div>
</div>
<script>
function responder(id) {
    document.getElementById('respuesta_padre').value = id;
    window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
