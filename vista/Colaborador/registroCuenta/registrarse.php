<?php
// Ensure session is started to display potential messages
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <title>Registrarse como Colaborador - Puntos de Reciclaje</title>
    <style>
        body {
            background-color: #f8f9fa; /* Light gray background */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding-top: 2rem; /* Add padding for scroll */
            padding-bottom: 2rem; /* Add padding for scroll */
            margin: 0;
        }
        .register-card {
            max-width: 550px; /* Slightly wider for more fields */
            width: 100%;
            padding: 2rem;
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }
        .logo-placeholder {
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            font-weight: bold;
            color: #0d6efd;
        }
        .form-label {
            font-size: 0.875rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-8 col-xl-7"> 
                <?php
                // This includes the formRegister.php content
                include (__DIR__ . '/formRegister.php');
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
    </script>
</body>
</html>