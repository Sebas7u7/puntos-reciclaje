
<?php
require_once(__DIR__ . '/navbarUser.php');
if (!isset($_SESSION["usuario"])) {
    header("Location: /PuntosReciclaje/index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bienvenido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h1 class="display-4">¡Bienvenido!</h1>
            </div>
        </div>
    </div>
</body>
</html>