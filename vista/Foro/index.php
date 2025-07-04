<?php
require_once(__DIR__ . '/../../persistencia/ForoTemaDAO.php');
require_once(__DIR__ . '/../../persistencia/ForoCategoriaDAO.php');
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: /puntos-reciclaje/index.php");
    exit();
}

$temaDAO = new ForoTemaDAO();
$temas = $temaDAO->listar();
$categoriaDAO = new ForoCategoriaDAO();
$categorias = $categoriaDAO->listar();

$temasFiltrados = [];
$categoriaSeleccionada = $_GET['categoria'] ?? '';

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
if ($categoriaSeleccionada === '') {
    $temasFiltrados = $temas;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Foro de la Comunidad</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f0fff4 0%, #e6ffe6 50%, #ccfc7b 100%);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        .foro-container {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-top: 3rem;
        }

        .foro-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .foro-header h2 {
            color: #2d6e33;
            font-weight: 700;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-outline-primary:hover {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
        }

        .form-select {
            border-radius: 10px;
        }

        .list-group-item {
            border-radius: 10px;
            margin-bottom: 1rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .list-group-item:hover {
            background: #f8fff8;
            transform: translateY(-2px);
        }

        .alert-info {
            margin-top: 2rem;
            text-align: center;
        }

        .form-filtro {
            background: #ffffff;
            padding: 1rem 1.5rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.07);
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>

<?php include_once(__DIR__ . '/../Usuario/navbarUser.php'); ?>

<div class="container">
    <div class="foro-container">
        <div class="foro-header">
            <h2><i class="bi bi-chat-dots-fill me-2"></i>Foro de la Comunidad</h2>
            <a href="nuevoTema.php" class="btn btn-success">
                <i class="bi bi-plus-circle me-1"></i>Nuevo Tema
            </a>
        </div>

        <form method="get" class="form-filtro row g-2 align-items-center">
            <div class="col-md-8">
                <select name="categoria" class="form-select">
                    <option value="">Todas las categorías</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat->getIdCategoria() ?>" <?= ($categoriaSeleccionada == $cat->getIdCategoria()) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat->getNombre()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 text-md-end">
<button class="btn btn-success" type="submit">
                    <i class="bi bi-filter-circle me-1"></i>Filtrar
                </button>
            </div>
        </form>

        <div class="list-group">
            <?php foreach ($temasFiltrados as $tema): ?>
                <a href="verTema.php?id=<?= $tema->getIdTema() ?>" class="list-group-item list-group-item-action">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-1"><?= htmlspecialchars($tema->getTitulo()) ?></h5>
                        <small class="text-muted"><?= htmlspecialchars($tema->getFechaCreacion()) ?></small>
                    </div>
                    <p class="mb-1 text-muted">
                        Por <?= $tema->getUsuario() ? htmlspecialchars($tema->getUsuario()->getNombre()) : 'Usuario' ?>
                        <?= $tema->getCategoria() ? ' | ' . htmlspecialchars($tema->getCategoria()->getNombre()) : '' ?>
                    </p>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if (empty($temasFiltrados)): ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-1"></i>
                No hay resultados para la búsqueda o filtro seleccionado.
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
