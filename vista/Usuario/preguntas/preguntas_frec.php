<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comentarios (Simulación)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .comentario {
            margin-bottom: 1.5rem;
        }
        .respuesta {
            margin-left: 2rem;
            border-left: 2px solid #dee2e6;
            padding-left: 1rem;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
<?php
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
include(__DIR__ . '/../navbarUser.php');
if (!isset($_SESSION["usuario"])) {
    header("Location: /PuntosReciclaje/index.php");
    exit();
}
$comentario = new Comentario();
$comentarios = $comentario->listarPadres();
?>

<div class="container mt-5">
    <h3 class="mb-4">Preguntas frecuentes</h3>

    <!-- Nueva pregunta (comentario raíz) -->
    <div class="mb-4">
        <textarea class="form-control mb-2" id="pregunta-texto" placeholder="¿Tienes una pregunta? Escríbela aquí..."></textarea>
        <button class="btn btn-success btn-sm" onclick="publicarPregunta()">Publicar pregunta</button>
    </div>

    <?php foreach($comentarios as $padre): ?>
    <div class="comentario" id="comentario-<?= $padre->getId() ?>">
        <div class="d-flex justify-content-between">
            <strong><?= $padre->getUsuario()->getNombre() ?></strong>
            <small class="text-muted"><?= $padre->getFecha() ?></small>
        </div>
        <p><?= $padre->getContenido() ?></p>
        <button class="btn btn-sm btn-outline-secondary" onclick="mostrarFormulario(<?= $padre->getId() ?>)">Responder</button>

        <!-- Respuestas -->
        <div class="respuestas mt-3">
            <?php $respuestas = $comentario->listarHijos($padre->getId()); ?>
            <?php foreach($respuestas as $respuesta): ?>
            <div class="respuesta" id="comentario-<?= $respuesta->getId() ?>">
                <div class="d-flex justify-content-between">
                    <strong><?= $respuesta->getUsuario()->getNombre() ?></strong>
                    <small class="text-muted"><?= $respuesta->getFecha() ?></small>
                </div>
                <p><?= $respuesta->getContenido() ?></p>
                <button class="btn btn-sm btn-outline-secondary" onclick="mostrarFormulario(<?= $respuesta->getId() ?>)">Responder</button>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- Formulario de respuesta oculto -->
    <div id="formulario-respuesta" class="mt-3" style="display: none;">
        <textarea class="form-control mb-2" id="respuesta-texto" placeholder="Escribe tu respuesta..."></textarea>
        <button class="btn btn-primary btn-sm" onclick="publicarRespuesta()">Publicar</button>
        <button class="btn btn-secondary btn-sm" onclick="cancelarRespuesta()">Cancelar</button>
    </div>
</div>

<script>
let comentarioPadreId = null;

function mostrarFormulario(idComentarioPadre) {
    comentarioPadreId = idComentarioPadre;

    const formulario = document.getElementById("formulario-respuesta");
    const contenedor = document.querySelector(`#comentario-${idComentarioPadre} .respuestas`);

    if (!contenedor) return;

    contenedor.appendChild(formulario);
    formulario.style.display = "block";
    document.getElementById("respuesta-texto").value = "";
}

function cancelarRespuesta() {
    document.getElementById("formulario-respuesta").style.display = "none";
}

function publicarRespuesta() {
    const texto = document.getElementById("respuesta-texto").value;
    if (texto.trim() === "") return;

    fetch("guardar_respuesta.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `contenido=${encodeURIComponent(texto)}&padre=${comentarioPadreId}`
    })
    .then(res => res.text())
    .then(msg => {
        alert(msg);
        location.reload();
    });
}

function publicarPregunta() {
    const texto = document.getElementById("pregunta-texto").value;
    if (texto.trim() === "") return;

    fetch("guardar_respuesta.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `contenido=${encodeURIComponent(texto)}&padre=`
    })
    .then(res => res.text())
    .then(msg => {
        alert(msg);
        location.reload();
    });
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
