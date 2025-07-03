<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <title>Registrarse como Colaborador - Puntos de Reciclaje</title>
</head>
<body>
    <?php
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Pragma: no-cache');
    include(__DIR__ . '/../navbarUser.php'); //
    // Verificación de seguridad: si no hay un colaborador en la sesión, redirigir al login.
    if (!isset($_SESSION["usuario"])) {
        header("Location: /PuntosReciclaje/index.php");
        exit();
    }
    ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-8 col-xl-7"> 
                <?php
                $publicacion = new Publicacion();
                $publicaciones = $publicacion -> consultar_por_tipo("video");
                include (__DIR__ . '/tablePublicacion.php');
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
    </script>
</body>
</html>