<div class="card register-card">
    <div class="card-body">
        <div class="logo-placeholder">
            <img src="https://www.todoimpresoras.com/wp-content/uploads/2018/02/beneficios-utilizar-papel-reciclado-en-las-empresas.png" alt="Logo Puntos Reciclaje" style="max-width: 70px; height: auto; margin-bottom: 1rem;">
            <h4>Puntos de Reciclaje</h4>
        </div>
        <h5 class="card-title text-center mb-4">Registrarse como Usuario</h5>

        <?php
        // Display messages if any (e.g., if redirected back to this form on error)
        if (isset($_SESSION['message']) && isset($_SESSION['message_type'])) {
            echo "<div class='alert alert-" . htmlspecialchars($_SESSION['message_type']) . " alert-dismissible fade show' role='alert'>";
            echo htmlspecialchars($_SESSION['message']);
            echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
            echo "</div>";
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
        }
        ?>

        <form method="post" action="crearUsuario.php">
            <div class="mb-3">
                <label class="form-label" for="userCorreo">Correo Electrónico</label>
                <input type="email" id="userCorreo" name="correo" class="form-control" placeholder="tu@correo.com" required />
            </div>
            <div class="mb-3">
                <label class="form-label" for="userClave">Contraseña.</label>
                <input type="password" id="userClave" name="clave" class="form-control" placeholder="mínimo 5 caracteres, al menos 1 letra y 1 número." required />
            </div>
            <div class="mb-3">
                <label class="form-label" for="userConfirmClave">Confirmar Contraseña</label>
                <input type="password" id="userConfirmClave" name="confirm_clave" class="form-control" placeholder="Confirma tu contraseña" required />
            </div>
            <div class="mb-3">
                <label class="form-label" for="userNombre">Nombre</label>
                <input type="text" id="userNombre" name="nombre" class="form-control" placeholder="Tu nombre" required />
            </div>
            <div class="mb-3">
                <label class="form-label" for="userApellido">Apellido</label>
                <input type="text" id="userApellido" name="apellido" class="form-control" placeholder="Tu apellido" required />
            </div>
            
            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-success btn-lg" name="registrar">Registrarse</button>
            </div>

            <hr class="my-4">

            <div class="text-center">
                <p class="mb-1">¿Ya tienes una cuenta?</p>
                <a href="/puntos-reciclaje/index.php">Iniciar Sesión</a>
            </div>
        </form>
    </div>
</div>