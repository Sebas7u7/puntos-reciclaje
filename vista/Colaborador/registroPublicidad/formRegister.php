<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="update-form-card">
                <h2 class="text-center text-success">Publicar campañas / noticias</h2>
                
                <?php
                // Display messages if any
                if (isset($_SESSION['debug_auth']) && !empty($_SESSION['debug_auth'])) {
                    echo '<div class="alert alert-warning mt-3" role="alert">';
                    echo '<h4 class="alert-heading">Información de Depuración de Autenticación:</h4><pre>';
                    foreach ($_SESSION['debug_auth'] as $message) {
                        echo htmlspecialchars($message) . "\n";
                    }
                    echo '</pre></div>';
                    unset($_SESSION['debug_auth']);
                }

                if (isset($_SESSION['message']) && isset($_SESSION['message_type'])) {
                    echo "<div class='alert alert-".htmlspecialchars($_SESSION['message_type'])." alert-dismissible fade show' role='alert'>";
                    echo htmlspecialchars($_SESSION['message']);
                    echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
                    echo "</div>";
                    unset($_SESSION['message']);
                    unset($_SESSION['message_type']);
                }
                ?>
                
                <form method="POST" action="publicar.php" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Nombre de campaña/noticia:</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción:</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo:</label>
                        <select class="form-select" id="tipo" name="tipo" required>
                            <option value="" selected disabled>Seleccione un tipo</option>
                            <option value="campaña">Campaña</option>
                            <option value="noticia">Noticia</option>
                            <option value="evento">Evento</option>
                            <option value="informacion">Información/recursos</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="fecha_public" class="form-label">Fecha de publicación:</label>
                        <input type="date" class="form-control" id="fecha_public" name="fecha_public" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="enlace" class="form-label">Enlace:</label>
                        <input type="url" class="form-control" id="enlace" name="enlace" placeholder="https://ejemplo.com">
                    </div>
                    
                    <div class="mb-3">
                        <label for="imagen" class="form-label">Imagen (opcional):</label>
                        <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" name="registrar_publicidad" class="btn btn-custom btn-lg">
                            <i class="bi bi-megaphone-fill"></i> Publicar
                        </button>
                        <a href="publicar.php" class="btn btn-outline-success">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>