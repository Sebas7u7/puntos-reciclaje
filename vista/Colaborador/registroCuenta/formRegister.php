<div class="card register-card border-0 shadow" style="border-radius: 1rem; background-color: #ffffff;">
    <div class="card-body p-4">
        <div class="logo-placeholder text-center mb-3">
            <img src="https://www.todoimpresoras.com/wp-content/uploads/2018/02/beneficios-utilizar-papel-reciclado-en-las-empresas.png"
                alt="Logo Puntos Reciclaje" style="max-width: 70px; height: auto;">
            <h4 class="mt-2 fw-bold text-success">Puntos de Reciclaje</h4>
        </div>

        <h5 class="card-title text-center mb-4 fw-semibold" style="color: #014421;">Registrarse como Colaborador</h5>

        <?php
        if (isset($_SESSION['message']) && isset($_SESSION['message_type'])) {
            echo "<div class='alert alert-" . htmlspecialchars($_SESSION['message_type']) . " alert-dismissible fade show' role='alert'>";
            echo htmlspecialchars($_SESSION['message']);
            echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
            echo "</div>";
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
        }
        ?>

        <form method="post" action="crearColaborador.php">
            <div class="mb-3">
                <label class="form-label fw-semibold text-success" for="colabCorreo">Correo Electrónico</label>
                <input type="email" id="colabCorreo" name="correo" class="form-control border-success"
                    placeholder="tu@correo.com" required style="background-color: #f7fff0;">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-success" for="colabClave">Contraseña</label>
                <input type="password" id="colabClave" name="clave" class="form-control border-success"
                    placeholder="Crea una contraseña" required style="background-color: #f7fff0;">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-success" for="colabConfirmClave">Confirmar Contraseña</label>
                <input type="password" id="colabConfirmClave" name="confirm_clave" class="form-control border-success"
                    placeholder="Confirma tu contraseña" required style="background-color: #f7fff0;">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-success" for="colabNombre">Nombre de la Entidad/Persona</label>
                <input type="text" id="colabNombre" name="nombre" class="form-control border-success"
                    placeholder="Nombre del colaborador" required style="background-color: #f7fff0;">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-success" for="colabServicio">Servicio Ofrecido</label>
                <input type="text" id="colabServicio" name="servicio_ofrecido" class="form-control border-success"
                    placeholder="Ej: Recolección, Centro de Acopio" required style="background-color: #f7fff0;">
            </div>

            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-success btn-lg fw-semibold" name="registrar"
                    style="background-color: #7ed957; border: none;">Registrarse como Colaborador</button>
            </div>

            <hr class="my-4">

            <div class="text-center">
                <p class="mb-1 text-muted">¿Ya tienes una cuenta?</p>
                <a href="/puntos-reciclaje/index.php" class="text-success fw-semibold text-decoration-none">Iniciar Sesión</a>
            </div>
        </form>
    </div>
</div>
