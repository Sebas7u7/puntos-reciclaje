<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="update-form-card">
                <h2 class="text-center">Clasificar residuos</h2>
<?php
$residuo = new Residuo();

if (isset($_POST['clasificar_residuo'])) {
    $residuoC = $residuo->clasificar_nombre($_POST["nombreR"]);
    include("tablaResiduos.php");
}
$residuos = $residuo->listar();
?>
<style>
    body {
        background: linear-gradient(to right, #e6ffe6, #f4fff4);
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

    .btn-info {
        background-color: #7ed957 !important;
        border-color: #7ed957 !important;
        color: white;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    .btn-info:hover {
        background-color: #6cc748 !important;
        border-color: #6cc748 !important;
    }

    .datalist {
        font-style: italic;
    }
</style>

                <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" enctype="multipart/form-data">
                    <div class="mb-3">
                            <label for="nombreR" class="form-label">Nombre residuo</label>
                            <input type="text" id="nombreR" name="nombreR" class="form-control"
                                list="nombreR_list" placeholder="Seleccione o escriba..." autocomplete="off">
                            <datalist id="nombreR_list">
                                <?php foreach($residuos as $temp): ?>
                                <option value="<?= htmlspecialchars($temp->getNombre()) ?>"></option>
                                <?php endforeach; ?>
                            </datalist>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" name="clasificar_residuo"
                            class="btn btn-info btn-lg">Clasificar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>