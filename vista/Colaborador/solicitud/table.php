<div class="table-responsive-lg rounded-3 shadow-sm mb-4" style="overflow-x: auto;">
    <table class="table table-hover table-bordered mb-0" style="min-width: 900px;">
        <thead class="text-white" style="background-color: #014421;">
            <tr>
                <th class="align-middle text-nowrap">ID</th>
                <th class="align-middle text-nowrap">Dirección</th>
                <th class="align-middle text-nowrap">Fecha Solicitud</th>
                <th class="align-middle text-nowrap">Usuario</th>
                <th class="align-middle text-nowrap">Residuo</th>
                <th class="align-middle text-nowrap">Cantidad</th>
                <th class="align-middle text-nowrap">Comentarios</th>
                <th class="align-middle text-nowrap">Fecha Programada</th>
                <th class="align-middle text-nowrap">Estado</th>
                <th class="align-middle text-nowrap">Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($solicitudes as $solicitud): ?>
            <tr>
                <td class="align-middle text-nowrap">
                    <?= htmlspecialchars($solicitud->getId()) ?>
                </td>
                <td class="align-middle">
                    <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        <?= htmlspecialchars($solicitud->getDireccion()) ?>
                    </div>
                </td>
                <td class="align-middle text-nowrap">
                    <?= htmlspecialchars($solicitud->getFechaSolicitud()) ?>
                </td>
                <td class="align-middle">
                    <div style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        <?= htmlspecialchars($solicitud->getUsuario() ? $solicitud->getUsuario()->getNombre() : 'Usuario eliminado') ?>
                    </div>
                </td>
                <td class="align-middle text-nowrap">
                    <?= htmlspecialchars($solicitud->getResiduo() ? $solicitud->getResiduo()->getNombre() : 'Residuo eliminado') ?>
                </td>
                <td class="align-middle text-nowrap">
                    <?= htmlspecialchars($solicitud->getCantidad()) ?>
                </td>
                <td class="align-middle">
                    <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        <?= htmlspecialchars($solicitud->getComentarios()) ?>
                    </div>
                </td>
                <td class="align-middle text-nowrap">
                    <form method="POST" action="programarSolicitud.php" style="display:inline;" onsubmit="return validarFechaProgramada(this)">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($solicitud->getId()) ?>">
                        <input type="datetime-local" name="fecha_programada" class="form-control form-control-sm" value="<?= $solicitud->getFechaProgramada() ? htmlspecialchars(date('Y-m-d\TH:i', strtotime($solicitud->getFechaProgramada()))) : '' ?>" required min="<?= date('Y-m-d\TH:i') ?>">
                </td>
                <td class="align-middle text-nowrap">
                    <span class="badge 
                        <?= $solicitud->getEstado() == 'pendiente' ? 'bg-warning text-dark' : 
                           ($solicitud->getEstado() == 'programado' ? 'bg-info text-dark' : 
                           ($solicitud->getEstado() == 'completado' ? 'bg-success' : 'bg-secondary')) ?>">
                        <?= htmlspecialchars($solicitud->getEstado()) ?>
                    </span>
                </td>
                <td class="align-middle text-nowrap">
                        <button type="submit" class="btn btn-sm btn-custom">
                            <i class="bi bi-calendar-check"></i> <span class="d-none d-md-inline">Programar</span>
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
function validarFechaProgramada(form) {
    const input = form.querySelector('input[name="fecha_programada"]');
    if (!input.value) {
        alert('Debe ingresar una fecha y hora.');
        return false;
    }
    const fechaIngresada = new Date(input.value);
    const ahora = new Date();
    if (fechaIngresada <= ahora) {
        alert('La fecha y hora programada deben ser posteriores a la fecha y hora actual.');
        return false;
    }
    return true;
}
</script>
