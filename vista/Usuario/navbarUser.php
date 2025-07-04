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
if (isset($_GET["cerrarSesion"])||!isset($_SESSION["usuario"])) {
    $_SESSION = [];
    session_destroy();

    header("Location: /puntos-reciclaje/index.php");
    exit();
}
$usuario = $_SESSION["usuario"];
$cuenta = $_SESSION["cuenta"];
?>

<!-- Estilos personalizados para navbar ecológico -->
<style>
    .navbar {
        background: #ccfc7b;
        box-shadow: 0 4px 8px rgba(0, 128, 0, 0.1);
        padding: 0.75rem 1rem;
    }

    .nav-link {
        color: #155724 !important;
        font-weight: 500;
        margin-right: 1rem;
        transition: background-color 0.3s, color 0.3s;
        border-radius: 0.5rem;
        padding: 0.5rem 0.75rem;
    }

    .nav-link:hover {
        background-color: #d4edda;
        color: #0c4128 !important;
    }

    .navbar-brand img {
        border-radius: 50%;
        width: 35px;
        height: 35px;
    }

    .navbar-brand span {
        font-weight: 600;
        color: #0c4128;
        font-size: 1.1rem;
    }

    .navbar-text {
        color: #0c4128;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .btn-logout {
        background-color: #28a745;
        color: white !important;
        border-radius: 8px;
        padding: 0.45rem 0.9rem;
        font-size: 0.9rem;
        border: none;
        transition: background-color 0.3s;
    }

    .btn-logout:hover {
        background-color: #218838;
        color: #fff !important;
    }

    .navbar-toggler {
        border-color: rgba(21, 87, 36, 0.4);
    }

    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%230c4128' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%280, 0, 0, 0.5%29' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }
</style>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="/puntos-reciclaje/vista/Usuario/indexUsuario.php">
            <span class="ms-2 d-none d-sm-inline">Inicio</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarUsuario" 
                aria-controls="navbarUsuario" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarUsuario">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/puntos-reciclaje/vista/Usuario/actualizarDatos.php">
                        <i class="bi bi-person-gear"></i> Mis datos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/puntos-reciclaje/vista/Usuario/indexUsuario.php">
                        <i class="bi bi-house-door"></i> Inicio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/puntos-reciclaje/vista/Usuario/clasificacionResiduo/clasificacion.php">
                        <i class="bi bi-recycle"></i> Clasificación
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/puntos-reciclaje/vista/Usuario/mapeo/mapearPuntos.php" target="_blank">
                        <i class="bi bi-map"></i> Mapa
                    </a>
                </li>
                <!-- Nueva opción: Solicitar Recolección Puerta a Puerta -->
                <li class="nav-item">
                    <a class="nav-link" href="/puntos-reciclaje/vista/Usuario/SolicitudRecoleecion/solicitarRecoleccion.php">
                        <i class="bi bi-truck"></i> Solicitar Recolección
                    </a>
                </li>
                <!-- Nueva opción: Ver Publicaciones -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownPublicaciones" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-newspaper"></i> Publicaciones
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownPublicaciones">
                        <li><a class="dropdown-item" href="/puntos-reciclaje/vista/Usuario/publicacion/publicacionNoticia.php?tipo=campaña"><i class="bi bi-bullhorn"></i> Campañas</a></li>
                        <li><a class="dropdown-item" href="/puntos-reciclaje/vista/Usuario/publicacion/publicacionNoticia.php?tipo=noticia"><i class="bi bi-megaphone"></i> Noticias</a></li>
                        <li><a class="dropdown-item" href="/puntos-reciclaje/vista/Usuario/publicacion/publicacionNoticia.php?tipo=evento"><i class="bi bi-calendar-event"></i> Eventos</a></li>
                        <li><a class="dropdown-item" href="/puntos-reciclaje/vista/Usuario/publicacion/publicacionNoticia.php?tipo=informacion"><i class="bi bi-info-circle"></i> Información</a></li>
                    </ul>
                </li>
                <!-- Nueva opción: Preguntas Frecuentes / Comentarios -->
                <li class="nav-item">
                    <a class="nav-link" href="/puntos-reciclaje/vista/Usuario/preguntas/preguntas_frec.php">
                        <i class="bi bi-question-circle"></i> Preguntas Frecuentes
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center">
                <span class="navbar-text me-3 d-none d-lg-block">
                    <i class="bi bi-person-circle"></i> <?= htmlspecialchars($usuario->getNombre()); ?>
                </span>
                <a class="btn btn-logout" href="/puntos-reciclaje/index.php?cerrarSesion=1">
                    <i class="bi bi-box-arrow-right"></i> <span class="d-none d-sm-inline">Salir</span>
                </a>
            </div>
        </div>
    </div>
</nav>
