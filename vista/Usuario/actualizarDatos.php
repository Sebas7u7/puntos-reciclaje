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

// Refrescar datos del usuario desde la base de datos antes de mostrar el formulario
$conexion = new Conexion();
$conexion->abrirConexion();
$usuarioDAO = new UsuarioDAO();
$datos_actualizados = $usuarioDAO->consultarPorId($conexion, $usuario->getIdUsuario());
$conexion->cerrarConexion();
if ($datos_actualizados) {
    if (isset($datos_actualizados['nombre'])) $usuario->setNombre($datos_actualizados['nombre']);
    if (isset($datos_actualizados['apellido'])) $usuario->setApellido($datos_actualizados['apellido']);
    if (isset($datos_actualizados['telefono'])) $usuario->setTelefono($datos_actualizados['telefono']);
    if (isset($datos_actualizados['direccion'])) $usuario->setDireccion($datos_actualizados['direccion']);
    if (isset($datos_actualizados['nickname'])) $usuario->setNickname($datos_actualizados['nickname']);
    if (isset($datos_actualizados['foto_perfil'])) $usuario->setFotoPerfil($datos_actualizados['foto_perfil']);
    $_SESSION["usuario"] = $usuario;
}

$mensaje = "";
$tipo_mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_usuario'])) {
    $nuevo_nombre = trim($_POST['nombre']);
    $nuevo_apellido = trim($_POST['apellido']);
    $nuevo_telefono = trim($_POST['telefono']);
    $nueva_direccion = trim($_POST['direccion']);
    $nuevo_nickname = trim($_POST['nickname']);
    $foto_perfil = $usuario->getFotoPerfil(); // Valor por defecto
    $foto_subida = false;

    // Validaciones
    if (empty($nuevo_nombre) || empty($nuevo_apellido) || empty($nuevo_telefono) || empty($nueva_direccion) || empty($nuevo_nickname)) {
        $mensaje = "Todos los campos (nombre, apellido, teléfono, dirección, nickname) son obligatorios.";
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
                $destino = realpath(__DIR__ . '/../../img/perfiles/');
                if ($destino === false) {
                    $destino = __DIR__ . '/../../img/perfiles/';
                } else {
                    $destino .= '/';
                }
                if (!is_dir($destino)) {
                    mkdir($destino, 0777, true);
                }
                $nuevo_nombre_archivo = 'usuario_' . $usuario->getIdUsuario() . '_' . time() . '.' . $ext;
                $ruta_final = $destino . $nuevo_nombre_archivo;
                if (move_uploaded_file($tmp_name, $ruta_final)) {
                    $foto_perfil = 'img/perfiles/' . $nuevo_nombre_archivo;
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
            $exito = $usuarioDAO->actualizarDatosCompletos($conexion, $usuario->getIdUsuario(), $nuevo_nombre, $nuevo_apellido, $nuevo_telefono, $nueva_direccion, $nuevo_nickname, $foto_perfil);
            $conexion->cerrarConexion();
            if ($exito) {
                // Actualizar objeto y sesión
                $usuario->setNombre($nuevo_nombre);
                $usuario->setApellido($nuevo_apellido);
                $usuario->setTelefono($nuevo_telefono);
                $usuario->setDireccion($nueva_direccion);
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <title>Actualizar Información - Puntos de Reciclaje</title>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    
    body {
        background: linear-gradient(135deg, #f0fff4 0%, #e6ffe6 50%, #ccfc7b 100%);
        font-family: 'Inter', 'Segoe UI', sans-serif;
        min-height: 100vh;
        padding: 2rem 0;
    }

    .update-form-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 
            0 20px 40px rgba(0, 128, 0, 0.1),
            0 0 0 1px rgba(255, 255, 255, 0.1);
        overflow: hidden;
        margin-top: 2rem;
    }

    .update-header {
        background: linear-gradient(135deg, #ccfc7b 0%, #28a745 100%);
        padding: 2.5rem 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .update-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }

    .update-header h2 {
        color: #155724;
        font-weight: 700;
        font-size: 2.2rem;
        margin: 0;
        position: relative;
        z-index: 1;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .update-header .subtitle {
        color: #0c4128;
        font-size: 1.1rem;
        margin-top: 0.5rem;
        opacity: 0.9;
        position: relative;
        z-index: 1;
    }

    .profile-section {
        padding: 2rem;
        text-align: center;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-bottom: 1px solid #e9ecef;
    }

    .profile-img-container {
        position: relative;
        display: inline-block;
        margin-bottom: 1rem;
    }

    .profile-img-preview {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        border: 4px solid #28a745;
        object-fit: cover;
        box-shadow: 
            0 10px 30px rgba(40, 167, 69, 0.2),
            0 0 0 8px rgba(40, 167, 69, 0.1);
        transition: all 0.3s ease;
    }

    .profile-img-preview:hover {
        transform: scale(1.05);
        box-shadow: 
            0 15px 40px rgba(40, 167, 69, 0.3),
            0 0 0 8px rgba(40, 167, 69, 0.2);
    }

    .profile-placeholder {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        background: linear-gradient(135deg, #e9ecef 0%, #f8f9fa 100%);
        border: 4px solid #28a745;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: #6c757d;
        box-shadow: 
            0 10px 30px rgba(40, 167, 69, 0.2),
            0 0 0 8px rgba(40, 167, 69, 0.1);
    }

    .form-section {
        padding: 2.5rem;
        background: white;
    }

    .form-floating {
        margin-bottom: 1.5rem;
    }

    .form-floating > .form-control {
        height: 58px;
        padding: 1rem 0.75rem;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        background: #f8f9fa;
        transition: all 0.3s ease;
        font-size: 1rem;
    }

    .form-floating > .form-control:focus {
        border-color: #28a745;
        background: white;
        box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.15);
        transform: translateY(-2px);
    }

    .form-floating > label {
        padding: 1rem 0.75rem;
        color: #6c757d;
        font-weight: 500;
    }

    .file-upload-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 2px dashed #28a745;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    .file-upload-section:hover {
        background: linear-gradient(135deg, #e8f5e8 0%, #d4edda 100%);
        border-color: #20c997;
    }

    .file-upload-section label {
        font-weight: 600;
        color: #28a745;
        margin-bottom: 0.5rem;
        display: block;
    }

    .file-upload-section .form-control {
        border: none;
        background: transparent;
        padding: 0.5rem;
    }

    .btn-group-custom {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
        border-radius: 12px;
        padding: 1rem 2rem;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
        background: linear-gradient(135deg, #218838 0%, #1ea97c 100%);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        border: none;
        border-radius: 12px;
        padding: 1rem 2rem;
        font-weight: 600;
        font-size: 1.1rem;
        color: white;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-secondary:hover {
        transform: translateY(-2px);
        background: linear-gradient(135deg, #5a6268 0%, #3d4349 100%);
        color: white;
    }

    .alert {
        border-radius: 12px;
        border: none;
        padding: 1.25rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .alert-success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
        border-left: 4px solid #28a745;
    }

    .alert-danger {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        color: #721c24;
        border-left: 4px solid #dc3545;
    }

    .form-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        z-index: 10;
    }

    .input-group-custom {
        position: relative;
    }

    @media (max-width: 768px) {
        .btn-group-custom {
            grid-template-columns: 1fr;
        }
        
        .profile-img-preview, .profile-placeholder {
            width: 120px;
            height: 120px;
        }
        
        .update-header h2 {
            font-size: 1.8rem;
        }
        
        .form-section {
            padding: 1.5rem;
        }
    }

    .floating-elements {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: -1;
    }

    .floating-elements::before {
        content: '';
        position: absolute;
        top: 10%;
        left: 10%;
        width: 100px;
        height: 100px;
        background: radial-gradient(circle, rgba(204, 252, 123, 0.1) 0%, transparent 70%);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
    }

    .floating-elements::after {
        content: '';
        position: absolute;
        bottom: 20%;
        right: 15%;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(40, 167, 69, 0.1) 0%, transparent 70%);
        border-radius: 50%;
        animation: float 8s ease-in-out infinite reverse;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(5deg); }
    }
</style>

</head>
<body>
    <div class="floating-elements"></div>
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="update-form-container">
                    <!-- Header Section -->
                    <div class="update-header">
                        <h2>✨ Actualizar Mis Datos</h2>
                        <p class="subtitle">Mantén tu información actualizada para una mejor experiencia</p>
                    </div>

                    <!-- Profile Section -->
                    <div class="profile-section">
                        <div class="profile-img-container">
                            <?php 
                            $foto = $usuario->getFotoPerfil();
                            $foto_path = $foto ? realpath(__DIR__ . '/../../' . $foto) : false;
                            if ($foto && $foto_path && file_exists($foto_path)) : ?>
                                <img src="/<?php echo htmlspecialchars($foto); ?>" alt="Foto de perfil actual" class="profile-img-preview">
                            <?php else: ?>
                                <div class="profile-placeholder">
                                    <i class="bi bi-person-circle"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <h4 style="color: #28a745; margin: 0;">¡Hola, <?php echo htmlspecialchars($usuario->getNombre()); ?>!</h4>
                        <p style="color: #6c757d; margin-top: 0.5rem;">Actualiza tu información personal cuando lo necesites</p>
                    </div>

                    <!-- Form Section -->
                    <div class="form-section">
                        <?php if ($mensaje): ?>
                            <div class="alert alert-<?php echo htmlspecialchars($tipo_mensaje); ?> alert-dismissible fade show" role="alert">
                                <i class="bi bi-<?php echo $tipo_mensaje === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill'; ?> me-2"></i>
                                <?php echo htmlspecialchars($mensaje); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="actualizarDatos.php" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="nombre" name="nombre" 
                                               value="<?php echo htmlspecialchars($usuario->getNombre()); ?>" 
                                               placeholder="Nombre" required>
                                        <label for="nombre"><i class="bi bi-person me-2"></i>Nombre</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="apellido" name="apellido" 
                                               value="<?php echo htmlspecialchars($usuario->getApellido()); ?>" 
                                               placeholder="Apellido" required>
                                        <label for="apellido"><i class="bi bi-person-badge me-2"></i>Apellido</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="telefono" name="telefono" 
                                               value="<?php echo htmlspecialchars($usuario->getTelefono()); ?>" 
                                               placeholder="Teléfono" required pattern="[0-9]{7,15}" 
                                               title="Solo números, 7-15 dígitos">
                                        <label for="telefono"><i class="bi bi-telephone me-2"></i>Teléfono</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="nickname" name="nickname" 
                                               value="<?php echo htmlspecialchars($usuario->getNickname()); ?>" 
                                               placeholder="Nickname" required pattern="[a-zA-Z0-9_\-]{3,20}" 
                                               title="3-20 caracteres alfanuméricos, guión o guión bajo">
                                        <label for="nickname"><i class="bi bi-at me-2"></i>Nickname</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-floating">
                                <input type="text" class="form-control" id="direccion" name="direccion" 
                                       value="<?php echo htmlspecialchars($usuario->getDireccion()); ?>" 
                                       placeholder="Dirección" required>
                                <label for="direccion"><i class="bi bi-geo-alt me-2"></i>Dirección</label>
                            </div>

                            <div class="file-upload-section">
                                <label for="foto_perfil">
                                    <i class="bi bi-camera-fill me-2"></i>
                                    Cambiar Foto de Perfil
                                </label>
                                <p style="color: #6c757d; margin-bottom: 1rem; font-size: 0.9rem;">
                                    Formatos aceptados: JPG, JPEG, PNG, GIF
                                </p>
                                <input type="file" class="form-control" id="foto_perfil" name="foto_perfil" accept="image/*">
                            </div>

                            <div class="btn-group-custom">
                                <button type="submit" name="actualizar_usuario" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Guardar Cambios
                                </button>
                                <a href="indexUsuario.php" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>
                                    Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Preview image before upload
        document.getElementById('foto_perfil').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.querySelector('.profile-img-preview, .profile-placeholder');
                    if (preview) {
                        if (preview.classList.contains('profile-placeholder')) {
                            preview.innerHTML = '<img src="' + e.target.result + '" class="profile-img-preview" style="width: 140px; height: 140px;">';
                        } else {
                            preview.src = e.target.result;
                        }
                    }
                };
                reader.readAsDataURL(file);
            }
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>