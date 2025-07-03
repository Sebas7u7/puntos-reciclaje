<?php
// Cabeceras anti-caché para evitar acceso tras logout o retroceso
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

require_once(__DIR__ . '/navbarUser.php'); 
require_once(__DIR__ . '/../../logica/Usuario.php');
require_once(__DIR__ . '/../../persistencia/UsuarioDAO.php');
require_once(__DIR__ . '/../../persistencia/Conexion.php');

if (!isset($_SESSION["usuario"])) {
    header("Location: /PuntosReciclaje/index.php");
    exit();
}
$usuario = $_SESSION["usuario"];

$mensaje = "";
$tipo_mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_usuario'])) {
    $nuevo_nombre = trim($_POST['nombre']);
    $nuevo_apellido = trim($_POST['apellido']);
    $nuevo_telefono = trim($_POST['telefono']);
    $nuevo_nickname = trim($_POST['nickname']);
    $foto_perfil = $usuario->getFotoPerfil(); // Valor por defecto
    $foto_subida = false;

    // Validaciones
    if (empty($nuevo_nombre) || empty($nuevo_apellido) || empty($nuevo_telefono) || empty($nuevo_nickname)) {
        $mensaje = "Todos los campos (nombre, apellido, teléfono, nickname) son obligatorios.";
        $tipo_mensaje = "danger";
    } elseif (!preg_match('/^[0-9]{7,15}$/', $nuevo_telefono)) {
        $mensaje = "El teléfono debe contener solo números (7-15 dígitos).";
        $tipo_mensaje = "danger";
    } elseif (!preg_match('/^[a-zA-Z0-9_\-]{3,20}$/', $nuevo_nickname)) {
        $mensaje = "El nickname debe tener entre 3 y 20 caracteres alfanuméricos, guiones o guiones bajos.";
        $tipo_mensaje = "danger";
    } else {
        // Manejo de foto de perfil (opcional)
        if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
            $tmp_name = $_FILES['foto_perfil']['tmp_name'];
            $nombre_archivo = basename($_FILES['foto_perfil']['name']);
            $ext = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));
            $permitidas = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($ext, $permitidas)) {
                $destino = __DIR__ . '/../../img/perfiles_usuario/';
                if (!is_dir($destino)) {
                    mkdir($destino, 0777, true);
                }
                $nuevo_nombre_archivo = 'usuario_' . $usuario->getIdUsuario() . '_' . time() . '.' . $ext;
                $ruta_final = $destino . $nuevo_nombre_archivo;
                if (move_uploaded_file($tmp_name, $ruta_final)) {
                    $foto_perfil = 'img/perfiles_usuario/' . $nuevo_nombre_archivo;
                    $foto_subida = true;
                } else {
                    $mensaje = "No se pudo guardar la foto de perfil.";
                    $tipo_mensaje = "danger";
                }
            } else {
                $mensaje = "Formato de imagen no permitido. Usa jpg, jpeg, png o gif.";
                $tipo_mensaje = "danger";
            }
        }
        if ($tipo_mensaje !== "danger") {
            // Actualizar en BD
            $conexion = new Conexion();
            $conexion->abrirConexion();
            $usuarioDAO = new UsuarioDAO();
            $exito = $usuarioDAO->actualizarDatosCompletos($conexion, $usuario->getIdUsuario(), $nuevo_nombre, $nuevo_apellido, $nuevo_telefono, $nuevo_nickname, $foto_perfil);
            $conexion->cerrarConexion();
            if ($exito) {
                // Actualizar objeto y sesión
                $usuario->setNombre($nuevo_nombre);
                $usuario->setApellido($nuevo_apellido);
                $usuario->setTelefono($nuevo_telefono);
                $usuario->setNickname($nuevo_nickname);
                $usuario->setFotoPerfil($foto_perfil);
                $_SESSION["usuario"] = $usuario;
                if ($tipo_mensaje !== "danger") {
                    $mensaje = "Datos actualizados correctamente.";
                    $tipo_mensaje = "success";
                }
            } else {
                $mensaje = "No se pudo actualizar la información. Intenta de nuevo.";
                $tipo_mensaje = "danger";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Actualizar Información - Puntos de Reciclaje</title>
    <style>
    body {
        background: linear-gradient(to right, #e6ffe6, #f4fff4); /* fondo ecológico */
        font-family: 'Segoe UI', sans-serif;
    }

    .update-form-card {
        margin-top: 60px;
        padding: 35px;
        border-radius: 1rem;
        background-color: #ffffff;
        box-shadow: 0 10px 30px rgba(0, 128, 0, 0.1);
        border-left: 6px solid #7ed957;
    }

    .update-form-card h2 {
        color: #014421;
        font-weight: 700;
        margin-bottom: 25px;
        text-align: center;
    }

    .form-label {
        font-weight: 600;
        color: #2e5e2d;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #cce5cc;
        transition: border-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }

    .form-control:focus {
        border-color: #7ed957;
        box-shadow: 0 0 0 0.2rem rgba(126, 217, 87, 0.25);
    }

    .btn-primary {
        background-color: #7ed957;
        border-color: #7ed957;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #6cc748;
        border-color: #6cc748;
        color: #ffffff;
    }

    .btn-secondary {
        background-color: #cce5cc;
        border-color: #cce5cc;
        color: #014421;
        font-weight: bold;
    }

    .btn-secondary:hover {
        background-color: #b6deb6;
        border-color: #b6deb6;
        color: #ffffff;
    }

    .profile-img-preview {
        max-width: 120px;
        max-height: 120px;
        border-radius: 50%;
        margin-bottom: 10px;
        border: 2px solid #7ed957;
        object-fit: cover;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border-color: #c3e6cb;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border-color: #f5c6cb;
    }
</style>

</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">
                <div class="update-form-card">
                    <h2 class="text-center">Actualizar Mis Datos</h2>
                    <?php if ($mensaje): ?>
                        <div class="alert alert-<?php echo htmlspecialchars($tipo_mensaje); ?> alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($mensaje); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <form method="POST" action="actualizarDatos.php" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario->getNombre()); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido:</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo htmlspecialchars($usuario->getApellido()); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono:</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($usuario->getTelefono()); ?>" required pattern="[0-9]{7,15}" title="Solo números, 7-15 dígitos">
                        </div>
                        <div class="mb-3">
                            <label for="nickname" class="form-label">Nickname:</label>
                            <input type="text" class="form-control" id="nickname" name="nickname" value="<?php echo htmlspecialchars($usuario->getNickname()); ?>" required pattern="[a-zA-Z0-9_\-]{3,20}" title="3-20 caracteres alfanuméricos, guión o guión bajo">
                        </div>
                        <div class="mb-3">
                            <label for="foto_perfil" class="form-label">Foto de Perfil (opcional):</label><br>
                            <?php if ($usuario->getFotoPerfil()): ?>
                                <img src="/<?php echo htmlspecialchars($usuario->getFotoPerfil()); ?>" alt="Foto de perfil actual" class="profile-img-preview"><br>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="foto_perfil" name="foto_perfil" accept="image/*">
                        </div>
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" name="actualizar_usuario" class="btn btn-primary btn-lg">Guardar Cambios</button>
                            <a href="indexUsuario.php" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>