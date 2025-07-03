<div class="card register-card">
    <div class="card-body">
        <div class="logo-placeholder">
            <img src="https://www.todoimpresoras.com/wp-content/uploads/2018/02/beneficios-utilizar-papel-reciclado-en-las-empresas.png"
                alt="Logo Puntos Reciclaje" style="max-width: 70px; height: auto; margin-bottom: 1rem;">
            <h4>Puntos de Reciclaje</h4>
        </div>
        <h5 class="card-title text-center mb-4">Registrarse como Colaborador</h5>

        <?php
        // Display messages if any
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
                <label class="form-label" for="colabCorreo">Correo Electrónico</label>
                <input type="email" id="colabCorreo" name="correo" class="form-control" placeholder="tu@correo.com"
                    required />
            </div>
            <div class="mb-3">
                <label class="form-label" for="colabClave">Contraseña</label>
                <input type="password" id="colabClave" name="clave" class="form-control"
                    placeholder="Crea una contraseña" required />
            </div>
            <div class="mb-3">
                <label class="form-label" for="colabConfirmClave">Confirmar Contraseña</label>
                <input type="password" id="colabConfirmClave" name="confirm_clave" class="form-control"
                    placeholder="Confirma tu contraseña" required />
            </div>
            <div class="mb-3">
                <label class="form-label" for="colabNombre">Nombre de la Entidad/Persona</label>
                <input type="text" id="colabNombre" name="nombre" class="form-control"
                    placeholder="Nombre del colaborador" required />
            </div>
            <div class="mb-3">
                <label class="form-label" for="colabServicio">Servicio Ofrecido</label>
                <input type="text" id="colabServicio" name="servicio_ofrecido" class="form-control"
                    placeholder="Ej: Recolección, Centro de Acopio" required />
            </div>

            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-info btn-lg" name="registrar">Registrarse como Colaborador</button>
            </div>

            <hr class="my-4">

            <div class="text-center">
                <p class="mb-1">¿Ya tienes una cuenta?</p>
                <a href="/puntos-reciclaje/index.php">Iniciar Sesión</a>
            </div>
        </form>
    </div>
</div>