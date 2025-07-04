<?php
require_once(__DIR__ . '/../../persistencia/ForoCategoriaDAO.php');
require_once(__DIR__ . '/../../persistencia/ForoTemaDAO.php');
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: /puntos-reciclaje/index.php");
    exit();
}
$categoriaDAO = new ForoCategoriaDAO();
$categorias = $categoriaDAO->listar();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $idCategoria = isset($_POST['categoria']) && $_POST['categoria'] !== '' ? $_POST['categoria'] : null;
    $idUsuario = $_SESSION['usuario']->getIdUsuario();
    $temaDAO = new ForoTemaDAO();
    $temaDAO->crear($titulo, $descripcion, $idUsuario, $idCategoria);
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Tema - Foro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
<?php include_once(__DIR__ . '/../Usuario/navbarUser.php'); ?>
<div class="container mt-5">
    <h2 class="mb-4">Crear Nuevo Tema</h2>
    <form method="post" class="border p-4 rounded bg-light">
        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="5" required></textarea>
        </div>
        <div class="mb-3">
            <label for="categoria" class="form-label">Categoría</label>
            <select class="form-select" id="categoria" name="categoria">
                <option value="">Sin categoría</option>
                <?php foreach($categorias as $cat): ?>
                    <option value="<?= $cat->getIdCategoria() ?>"><?= htmlspecialchars($cat->getNombre()) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success"><i class="bi bi-plus-circle"></i> Crear Tema</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
