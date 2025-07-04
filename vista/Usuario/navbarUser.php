<?php
require_once(__DIR__ . '/../../logica/Usuario.php');
require_once(__DIR__ . '/../../logica/Residuo.php');
require_once(__DIR__ . '/../../logica/Colaborador.php');
require_once(__DIR__ . '/../../logica/Punto_recoleccion.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (isset($_GET["cerrarSesion"]) || !isset($_SESSION["usuario"])) {
    $_SESSION = [];
    session_destroy();
    header("Location: /puntos-reciclaje/index.php");
    exit();
}

$usuario = $_SESSION["usuario"];
$cuenta = $_SESSION["cuenta"];
?>

<!-- Estilos del navbar ecológico -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    * {
        font-family: 'Inter', sans-serif;
    }

    .navbar {
        background: linear-gradient(135deg, #ccfc7b 0%, #28a745 100%);
        box-shadow: 0 8px 16px rgba(40, 167, 69, 0.2);
        padding: 0.75rem 3rem;
        border-radius: 0 0 15px 15px;
        margin-bottom: 2rem;
    }

    .navbar .nav-link {
        color: #155724 !important;
        font-weight: 500;
        border-radius: 10px;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }

    .navbar .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.3);
        color: #0c4128 !important;
    }

    .navbar-brand {
        font-weight: 600;
        color: #0c4128 !important;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
    }

    .navbar-brand img {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        margin-right: 0.5rem;
    }

    .navbar-text {
        color: #0c4128;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .btn-logout {
        background-color: #28a745;
        color: white !important;
        border-radius: 10px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        border: none;
        transition: background-color 0.3s ease;
    }

    .btn-logout:hover {
        background-color: #218838;
        color: #fff !important;
    }

    .navbar-toggler {
        border-color: rgba(21, 87, 36, 0.5);
    }

    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%230c4128' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%280, 0, 0, 0.5%29' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    .dropdown-menu {
        border-radius: 10px;
        border: none;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .dropdown-item:hover {
        background-color: #d4edda;
        color: #0c4128;
    }
</style>

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="/puntos-reciclaje/vista/Usuario/indexUsuario.php">
            <i class="bi bi-house-door-fill me-2"></i> Inicio
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarUsuario" 
                aria-controls="navbarUsuario" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarUsuario">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/puntos-reciclaje/vista/Usuario/actualizarDatos.php">
                        <i class="bi bi-person-gear me-1"></i> Mis datos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/puntos-reciclaje/vista/Usuario/clasificacionResiduo/clasificacion.php">
                        <i class="bi bi-recycle me-1"></i> Clasificación
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/puntos-reciclaje/vista/Usuario/mapeo/mapearPuntos.php" target="_blank">
                        <i class="bi bi-map me-1"></i> Mapa
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/puntos-reciclaje/vista/Usuario/SolicitudRecoleecion/solicitarRecoleccion.php">
                        <i class="bi bi-truck me-1"></i> Solicitar Recolección
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownPublicaciones" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-newspaper me-1"></i> Publicaciones
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownPublicaciones">
                        <li><a class="dropdown-item" href="/puntos-reciclaje/vista/Usuario/publicacion/publicacionNoticia.php?tipo=campaña"><i class="bi bi-bullhorn"></i> Campañas</a></li>
                        <li><a class="dropdown-item" href="/puntos-reciclaje/vista/Usuario/publicacion/publicacionNoticia.php?tipo=noticia"><i class="bi bi-megaphone"></i> Noticias</a></li>
                        <li><a class="dropdown-item" href="/puntos-reciclaje/vista/Usuario/publicacion/publicacionNoticia.php?tipo=evento"><i class="bi bi-calendar-event"></i> Eventos</a></li>
                        <li><a class="dropdown-item" href="/puntos-reciclaje/vista/Usuario/publicacion/publicacionNoticia.php?tipo=informacion"><i class="bi bi-info-circle"></i> Información</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/puntos-reciclaje/vista/Usuario/preguntas/preguntas_frec.php">
                        <i class="bi bi-question-circle me-1"></i> Preguntas Frecuentes
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center">
                <span class="navbar-text me-3 d-none d-lg-block">
                    <i class="bi bi-person-circle me-1"></i> <?= htmlspecialchars($usuario->getNombre()); ?>
                </span>
                <a class="btn btn-logout" href="/puntos-reciclaje/index.php?cerrarSesion=1">
                    <i class="bi bi-box-arrow-right"></i> <span class="d-none d-sm-inline">Salir</span>
                </a>
            </div>
        </div>
    </div>
</nav>
