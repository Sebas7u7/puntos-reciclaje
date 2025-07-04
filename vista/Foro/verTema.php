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
    $hasResponses = false;
    foreach ($respuestas as $r) {
        if ($r->getRespuestaPadre() == $padre) {
            $hasResponses = true;
            $nestedClass = $nivel > 0 ? 'nested-response' : '';
            echo '<div class="' . $nestedClass . '">';
            echo '<div class="response-card">';
            echo '<div class="response-header d-flex justify-content-between align-items-center">';
            echo '<div class="response-author">';
            echo '<i class="bi bi-person-circle" style="color: var(--primary-color);"></i>';
            echo '<span>' . ($r->getUsuario() ? htmlspecialchars($r->getUsuario()->getNombre()) : 'Usuario') . '</span>';
            echo '</div>';
            echo '<small class="response-date"><i class="bi bi-clock me-1"></i>' . htmlspecialchars($r->getFecha()) . '</small>';
            echo '</div>';
            echo '<div class="response-content">' . nl2br(htmlspecialchars($r->getContenido())) . '</div>';
            echo '<button class="reply-btn" onclick="responder(' . $r->getIdRespuesta() . ')">';
            echo '<i class="bi bi-reply me-1"></i>Responder';
            echo '</button>';
            echo '</div>';
            mostrarRespuestas($respuestas, $r->getIdRespuesta(), $nivel + 1);
            echo '</div>';
        }
    }
    
    if (!$hasResponses && $padre === null) {
        echo '<div class="no-responses">';
        echo '<i class="bi bi-chat-dots"></i>';
        echo '<h4>¡Sé el primero en responder!</h4>';
        echo '<p>Comparte tu opinión sobre este tema</p>';
        echo '</div>';
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
        
        .container-custom {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .back-btn {
            background: white;
            color: var(--primary-color);
            border: 2px solid var(--border-color);
            padding: 10px 20px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            margin-bottom: 2rem;
        }
        
        .back-btn:hover {
            background: var(--bg-light);
            color: var(--secondary-color);
            transform: translateY(-1px);
            box-shadow: var(--shadow);
        }
        
        .tema-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-color);
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .tema-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--accent-color) 100%);
        }
        
        .tema-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }
        
        .tema-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        
        .category-badge {
            background: linear-gradient(135deg, var(--accent-color) 0%, var(--primary-color) 100%);
            color: var(--text-dark);
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .tema-description {
            font-size: 1.1rem;
            line-height: 1.7;
            color: var(--text-dark);
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
        }
        
        .responses-header {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
        }
        
        .responses-title {
            color: var(--text-dark);
            font-weight: 600;
            font-size: 1.3rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .response-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }
        
        .response-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .response-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .response-author {
            font-weight: 600;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .response-date {
            color: var(--text-muted);
            font-size: 0.85rem;
        }
        
        .response-content {
            color: var(--text-dark);
            line-height: 1.6;
            margin-bottom: 1rem;
        }
        
        .reply-btn {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            color: var(--text-dark);
            border: none;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .reply-btn:hover {
            background: linear-gradient(135deg, var(--accent-color) 0%, var(--primary-color) 100%);
            transform: translateY(-1px);
            color: var(--text-dark);
        }
        
        .reply-form-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
        }
        
        .reply-form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-color) 0%, var(--primary-color) 100%);
        }
        
        .reply-form-title {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .form-control {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 15px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #fafafa;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(204, 252, 123, 0.1);
            background: white;
        }
        
        .submit-btn {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
            border: none;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: var(--shadow);
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px -3px rgba(40, 167, 69, 0.4);
            color: white;
        }
        
        .nested-response {
            margin-left: 2rem;
            border-left: 3px solid var(--border-color);
            padding-left: 1rem;
        }
        
        .no-responses {
            background: white;
            border: 2px dashed var(--border-color);
            border-radius: 16px;
            padding: 3rem;
            text-align: center;
            color: var(--text-muted);
            margin-bottom: 2rem;
        }
        
        .no-responses i {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
<?php include_once(__DIR__ . '/../Usuario/navbarUser.php'); ?>

<div class="container-custom">
    <a href="index.php" class="back-btn">
        <i class="bi bi-arrow-left"></i>
        Volver al foro
    </a>
    
    <div class="tema-card">
        <h1 class="tema-title"><?= htmlspecialchars($tema->getTitulo()) ?></h1>
        
        <div class="tema-meta">
            <div class="meta-item">
                <i class="bi bi-person-circle"></i>
                <span>Por <?= $tema->getUsuario() ? htmlspecialchars($tema->getUsuario()->getNombre()) : 'Usuario' ?></span>
            </div>
            <div class="meta-item">
                <i class="bi bi-calendar3"></i>
                <span><?= htmlspecialchars($tema->getFechaCreacion()) ?></span>
            </div>
            <?php if ($tema->getCategoria()): ?>
                <div class="category-badge">
                    <i class="bi bi-bookmark-fill me-1"></i>
                    <?= htmlspecialchars($tema->getCategoria()->getNombre()) ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="tema-description">
            <?= nl2br(htmlspecialchars($tema->getDescripcion())) ?>
        </div>
    </div>
    
    <div class="responses-header">
        <h2 class="responses-title">
            <i class="bi bi-chat-left-text" style="color: var(--primary-color);"></i>
            Respuestas
        </h2>
    </div>
    
    <div id="respuestas">
        <?php mostrarRespuestas($respuestas); ?>
    </div>
    
    <div class="reply-form-card">
        <h3 class="reply-form-title">
            <i class="bi bi-pencil-square" style="color: var(--primary-color);"></i>
            Únete a la conversación
        </h3>
        <form method="post">
            <input type="hidden" name="respuesta_padre" id="respuesta_padre" value="">
            <div class="mb-3">
                <textarea class="form-control" 
                          name="contenido" 
                          rows="4" 
                          required 
                          placeholder="Comparte tu opinión, experiencia o pregunta sobre este tema..."></textarea>
            </div>
            <button type="submit" class="submit-btn">
                <i class="bi bi-send me-2"></i>Publicar respuesta
            </button>
        </form>
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
