<?php
require_once(__DIR__ . '/../../persistencia/ForoTemaDAO.php');
require_once(__DIR__ . '/../../persistencia/ForoCategoriaDAO.php');
session_start();
// Solo permitir acceso a usuarios, no a colaboradores
if (!isset($_SESSION["usuario"])) {
    header("Location: /puntos-reciclaje/index.php");
    exit();
}
// Obtener todos los temas y categorías
$temaDAO = new ForoTemaDAO();
$temas = $temaDAO->listar();
$categoriaDAO = new ForoCategoriaDAO();
$categorias = $categoriaDAO->listar();

// Filtrar solo por categoría si corresponde
$temasFiltrados = [];
$categoriaSeleccionada = isset($_GET['categoria']) ? $_GET['categoria'] : '';
foreach ($temas as $tema) {
    $coincideCategoria = true;
    if ($categoriaSeleccionada !== '' && $tema->getCategoria()) {
        $coincideCategoria = $tema->getCategoria()->getIdCategoria() == $categoriaSeleccionada;
    } elseif ($categoriaSeleccionada !== '' && !$tema->getCategoria()) {
        $coincideCategoria = false;
    }
    if ($coincideCategoria) {
        $temasFiltrados[] = $tema;
    }
}
// Si no hay filtro, mostrar todos
if ($categoriaSeleccionada === '') {
    $temasFiltrados = $temas;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Foro de la Comunidad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
<?php include_once(__DIR__ . '/../Usuario/navbarUser.php'); ?>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Foro de la Comunidad</h2>
        <a href="nuevoTema.php" class="btn btn-success"><i class="bi bi-plus-circle"></i> Nuevo Tema</a>
    </div>
    <div class="mb-3">
        <form method="get" class="row g-2">
            <div class="col-auto">
                <select name="categoria" class="form-select">
                    <option value="">Todas las categorías</option>
                    <?php foreach($categorias as $cat): ?>
                        <option value="<?= $cat->getIdCategoria() ?>" <?= (isset($_GET['categoria']) && $_GET['categoria'] == $cat->getIdCategoria()) ? 'selected' : '' ?>><?= htmlspecialchars($cat->getNombre()) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i> Filtrar</button>
            </div>
        </form>
    </div>
    <div class="list-group">
        <?php foreach($temasFiltrados as $tema): ?>
            <a href="verTema.php?id=<?= $tema->getIdTema() ?>" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1"><?= htmlspecialchars($tema->getTitulo()) ?></h5>
                    <small><?= htmlspecialchars($tema->getFechaCreacion()) ?></small>
                </div>
                <p class="mb-1 text-muted">Por <?= $tema->getUsuario() ? htmlspecialchars($tema->getUsuario()->getNombre()) : 'Usuario' ?><?= $tema->getCategoria() ? ' | ' . htmlspecialchars($tema->getCategoria()->getNombre()) : '' ?></p>
            </a>
        <?php endforeach; ?>
        <?php if (empty($temasFiltrados)): ?>
            <div class="alert alert-info">No hay resultados para la búsqueda o filtro seleccionado.</div>
        <?php endif; ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
