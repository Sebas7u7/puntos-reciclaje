<?php
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
include(__DIR__ . '/../navbarUser.php');
require_once(__DIR__ . '/../../../logica/Publicacion.php');

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
    <title>Noticias - EcoGestor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #f0fff4 0%, #e6ffe6 50%, #ccfc7b 100%);
            font-family: 'Inter', sans-serif;
        }

        .titulo {
            margin-top: 40px;
            margin-bottom: 30px;
            color: #2d6e33;
            font-weight: 700;
            text-align: center;
        }

        .form-filtro {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }

        .form-select,
        .btn-outline-success {
            border-radius: 8px;
        }

        .btn-outline-success:hover {
            background-color: #28a745;
            color: white;
        }

        .contenedor-tabla {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .card-publicacion {
            margin-bottom: 20px;
        }

        .label-select {
            background-color: #28a745;
            color: white;
            border-radius: 8px 0 0 8px;
        }

        @media (max-width: 768px) {
            .form-filtro {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="titulo"><i class="bi bi-megaphone-fill me-2"></i>Gestión de Publicaciones</h2>

    <div class="row justify-content-center mb-4">
        <div class="col-md-8">
            <?php
            $tipos = [
                'campaña' => 'Campaña',
                'noticia' => 'Noticia',
                'evento' => 'Evento',
                'informacion' => 'Información',
                'recurso' => 'Recurso'
            ];
            $tipoSeleccionado = isset($_GET['tipo']) ? $_GET['tipo'] : 'noticia';
            ?>
            <form method="GET" class="form-filtro">
                <div class="input-group">
                    <label class="input-group-text label-select" for="tipo">Filtrar por tipo</label>
                    <select class="form-select" id="tipo" name="tipo">
                        <?php
                        foreach ($tipos as $key => $label) {
                            $selected = ($tipoSeleccionado == $key) ? 'selected' : '';
                            echo "<option value='$key' $selected>$label</option>";
                        }
                        ?>
                    </select>
                    <button class="btn btn-outline-success" type="submit">
                        <i class="bi bi-filter-circle me-1"></i>Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10 contenedor-tabla">
            <?php
            $publicacion = new Publicacion();
            $publicaciones = $publicacion->consultar_por_tipo(ucfirst($tipoSeleccionado));
            include(__DIR__ . '/tablePublicacion.php');
            ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
</script>
</body>
</html>
