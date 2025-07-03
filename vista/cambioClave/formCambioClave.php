<form method="post" action="validarClave.php">
    <div class="mb-4">
        <label for="changeCorreo" class="form-label" style="font-weight: 600; color: #2e5731;">
            Correo Electrónico
        </label>
        <input type="email" id="changeCorreo" name="correo"
            class="form-control border-success shadow-sm"
            placeholder="tu@correo.com" required
            style="background-color: #f7fff0;" />
    </div>

    <button type="submit" name="cambioClave"
        class="btn btn-lg w-100"
        style="background-color: #7ed957; color: #014421; font-weight: bold; border: none;">
        Cambiar Contraseña
    </button>
</form>
