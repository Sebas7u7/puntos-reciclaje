<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrarse como Usuario - Puntos de Reciclaje</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">

    <style>
        body {
            background: linear-gradient(to top, #ccfc7b, #ffffff);
            min-height: 100vh;
            margin: 0;
            padding: 2rem 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-card {
            background-color: #ffffff;
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 0 1rem rgba(0, 0, 0, 0.1);
            border: 2px solid #ccfc7b;
        }

        .logo-placeholder {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .logo-placeholder img {
            max-width: 70px;
            height: auto;
        }

        .logo-placeholder h4 {
            margin-top: 0.5rem;
            font-weight: bold;
            color: #014421;
        }

        .form-label {
            font-weight: 600;
            color: #2e5731;
        }

        .form-control {
            background-color: #f7fff0;
            border-color: #a8e6a1;
        }

        .btn-register {
            background-color: #7ed957;
            color: #014421;
            font-weight: 600;
            border: none;
        }

        .btn-register:hover {
            background-color: #6ecb49;
        }

        .text-links a {
            color: #014421;
            font-weight: 500;
            text-decoration: none;
        }

        .text-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-7 col-xl-6">
                <div class="register-card">
                    <?php include(__DIR__ . '/formRegister.php'); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
    </script>
</body>
</html>
