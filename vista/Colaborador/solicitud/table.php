<div class="table-responsive-lg rounded-3 shadow-sm mb-4" style="overflow-x: auto;">
    <table class="table table-hover table-bordered mb-0" style="min-width: 900px;">
        <thead class="text-white" style="background-color: #014421;">
            <tr>
                <th class="align-middle text-nowrap">ID</th>
                <th class="align-middle text-nowrap">Dirección</th>
                <th class="align-middle text-nowrap">Fecha Solicitud</th>
                <th class="align-middle text-nowrap">Usuario</th>
                <th class="align-middle text-nowrap">Residuo</th>
                <th class="align-middle text-nowrap">Fecha Programada</th>
                <th class="align-middle text-nowrap">Estado</th>
                <th class="align-middle text-nowrap">Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($solicitudes as $solicitud): ?>
            <form method="POST" action="programarSolicitud.php">
                <tr>
                    <td class="align-middle text-nowrap">
                        <?= htmlspecialchars($solicitud->getId()) ?>
                        <input type="hidden" name="id" value="<?= htmlspecialchars($solicitud->getId()) ?>">
                    </td>
                    <td class="align-middle">
                        <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <?= htmlspecialchars($solicitud->getDireccion()) ?>
                            <input type="hidden" name="direccion" value="<?= htmlspecialchars($solicitud->getDireccion()) ?>">
                        </div>
                    </td>
                    <td class="align-middle text-nowrap">
                        <?= htmlspecialchars($solicitud->getFechaSolicitud()) ?>
                        <input type="hidden" name="fecha_solicitud" value="<?= htmlspecialchars($solicitud->getFechaSolicitud()) ?>">
                    </td>
                    <td class="align-middle">
                        <div style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <?= htmlspecialchars($solicitud->getUsuario()->getNombre()) ?>
                            <input type="hidden" name="usuario_id" value="<?= htmlspecialchars($solicitud->getUsuario()->getIdUsuario()) ?>">
                        </div>
                    </td>
                    <td class="align-middle text-nowrap">
                        <?= htmlspecialchars($solicitud->getResiduo()->getNombre()) ?>
                        <input type="hidden" name="residuo_id" value="<?= htmlspecialchars($solicitud->getResiduo()->getId()) ?>">
                    </td>
                    <td class="align-middle text-nowrap">
                        <input type="datetime-local" 
                               class="form-control form-control-sm" 
                               name="fecha_programada" 
                               value="<?= $solicitud->getFechaProgramada() ? htmlspecialchars(date('Y-m-d\TH:i', strtotime($solicitud->getFechaProgramada()))) : '' ?>" 
                               min="<?= htmlspecialchars(date('Y-m-d\TH:i')) ?>"
                               style="min-width: 180px;">
                    </td>
                    <td class="align-middle text-nowrap">
                        <span class="badge 
                            <?= $solicitud->getEstado() == 'pendiente' ? 'bg-warning text-dark' : 
                               ($solicitud->getEstado() == 'programado' ? 'bg-info text-dark' : 
                               ($solicitud->getEstado() == 'completado' ? 'bg-success' : 'bg-secondary')) ?>">
                            <?= htmlspecialchars($solicitud->getEstado()) ?>
                        </span>
                        <input type="hidden" name="estado" value="<?= htmlspecialchars($solicitud->getEstado()) ?>">
                    </td>
                    <td class="align-middle text-nowrap">
                        <input type="hidden" name="correoUsuario" value="<?= htmlspecialchars($solicitud->getUsuario()->getCuenta()->getCorreo()) ?>">
                        <input type="hidden" name="colaborador_id" value="<?= htmlspecialchars($solicitud->getColaborador()->getIdColaborador()) ?>">
                        <button type="submit" class="btn btn-sm btn-custom">
                            <i class="bi bi-calendar-check"></i> <span class="d-none d-md-inline">Programar</span>
                        </button>
                    </td>
                </tr>
            </form>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>