<?php
require_once(__DIR__ . '/../../logica/Usuario.php');
require_once(__DIR__ . '/../../logica/Residuo.php');
require_once(__DIR__ . '/../../logica/Colaborador.php');
require_once(__DIR__ . '/../../logica/Punto_recoleccion.php');
session_start();
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

<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="/puntos-reciclaje/vista/Usuario/indexUsuario.php">
            <img src="/assets/images/logo.webp" style="width: 30px; height: 30px;" alt="Logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/puntos-reciclaje/vista/Usuario/actualizarDatos.php">Actualizar mis
                        datos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/puntos-reciclaje/vista/Usuario/indexUsuario.php">Ver puntos de
                        reciclaje</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                        href="/puntos-reciclaje/vista/Usuario/clasificacionResiduo/clasificacion.php">clasificacion de
                        residuos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/puntos-reciclaje/vista/Usuario/mapeo/mapearPuntos.php" target="_blank">
                        Ver ubicaciones en el mapa
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/puntos-reciclaje/index.php?cerrarSesion=1">Cerrar sesión</a>
                </li>
            </ul>
            <span class="navbar-text">
                <?php echo htmlspecialchars($usuario->getNombre()); ?>
            </span>
        </div>
    </div>
</nav>