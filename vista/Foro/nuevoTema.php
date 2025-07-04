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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #ccfc7b;
            --secondary-color: #28a745;
            --accent-color: #d4fc8a;
            --bg-light: #f9fdf4;
            --text-dark: #1f2937;
            --text-muted: #6b7280;
            --border-color: #e8f5d1;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 25px -3px rgba(0, 0, 0, 0.1);
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0fff4 0%, #e6ffe6 50%, #ccfc7b 100%);
            min-height: 100vh;
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 20px 20px;
            box-shadow: var(--shadow);
        }
        
        .page-title {
            font-weight: 600;
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
        }
        
        .page-subtitle {
            font-weight: 300;
            opacity: 0.9;
            font-size: 1.1rem;
        }
        
        .container-custom {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .form-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
        }
        
        .form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--accent-color) 100%);
        }
        
        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }
        
        .form-control {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #fafafa;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(204, 252, 123, 0.1);
            background: white;
        }
        
        .form-select {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #fafafa;
        }
        
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(204, 252, 123, 0.1);
            background: white;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .btn-create {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            color: white;
            transition: all 0.3s ease;
            box-shadow: var(--shadow);
        }
        
        .btn-create:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px -3px rgba(204, 252, 123, 0.4);
            color: white;
        }
        
        .btn-cancel {
            background: #f3f4f6;
            border: 2px solid #e5e7eb;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 500;
            color: var(--text-muted);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-cancel:hover {
            background: #e5e7eb;
            color: var(--text-dark);
            text-decoration: none;
            transform: translateY(-1px);
        }
        
        .input-icon {
            position: relative;
        }
        
        .input-icon i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            z-index: 10;
        }
        
        .input-icon .form-control,
        .input-icon .form-select {
            padding-left: 45px;
        }
        
        .breadcrumb-nav {
            background: white;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
        }
        
        .breadcrumb {
            margin: 0;
            background: none;
            padding: 0;
        }
        
        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .breadcrumb-item.active {
            color: var(--text-muted);
        }
        
        .form-hint {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }
        
        .button-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .form-floating {
            position: relative;
        }
        
        .form-floating > .form-control {
            height: calc(3.5rem + 2px);
            padding: 1rem 0.75rem;
        }
        
        .form-floating > label {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            padding: 1rem 0.75rem;
            pointer-events: none;
            border: 1px solid transparent;
            transform-origin: 0 0;
            transition: opacity .1s ease-in-out,transform .1s ease-in-out;
        }
    </style>
</head>
<body>
<?php include_once(__DIR__ . '/../Usuario/navbarUser.php'); ?>

<div class="page-header">
    <div class="container-custom">
        <h1 class="page-title">
            <i class="bi bi-plus-circle-fill me-3"></i>Crear Nuevo Tema
        </h1>
        <p class="page-subtitle mb-0">Comparte tu idea con la comunidad</p>
    </div>
</div>

<div class="container-custom">
    <nav class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="index.php">
                    <i class="bi bi-house me-1"></i>Foro
                </a>
            </li>
            <li class="breadcrumb-item active">Nuevo Tema</li>
        </ol>
    </nav>

    <div class="form-card">
        <form method="post">
            <div class="form-group">
                <label for="titulo" class="form-label">
                    <i class="bi bi-type me-2" style="color: var(--primary-color);"></i>Título del tema
                </label>
                <div class="input-icon">
                    <i class="bi bi-pencil"></i>
                    <input type="text" 
                           class="form-control" 
                           id="titulo" 
                           name="titulo" 
                           placeholder="Escribe un título llamativo para tu tema..."
                           required>
                </div>
                <div class="form-hint">Elige un título claro y descriptivo que capture la atención</div>
            </div>

            <div class="form-group">
                <label for="descripcion" class="form-label">
                    <i class="bi bi-text-paragraph me-2" style="color: var(--primary-color);"></i>Descripción
                </label>
                <textarea class="form-control" 
                          id="descripcion" 
                          name="descripcion" 
                          rows="6" 
                          placeholder="Describe tu tema en detalle. ¿Qué quieres discutir o compartir con la comunidad?"
                          required></textarea>
                <div class="form-hint">Explica tu idea de forma clara y detallada para generar más participación</div>
            </div>

            <div class="form-group">
                <label for="categoria" class="form-label">
                    <i class="bi bi-bookmark me-2" style="color: var(--primary-color);"></i>Categoría
                </label>
                <div class="input-icon">
                    <i class="bi bi-tag"></i>
                    <select class="form-select" id="categoria" name="categoria">
                        <option value="">🌟 Sin categoría específica</option>
                        <?php foreach($categorias as $cat): ?>
                            <option value="<?= $cat->getIdCategoria() ?>">
                                📂 <?= htmlspecialchars($cat->getNombre()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-hint">Selecciona la categoría que mejor se adapte a tu tema</div>
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-create">
                    <i class="bi bi-plus-circle me-2"></i>Crear Tema
                </button>
                <a href="index.php" class="btn btn-cancel">
                    <i class="bi bi-arrow-left me-2"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
