<?php
// Array de ubicaciones con nombre, latitud y longitud
session_start();
require_once(__DIR__ . '/../../../logica/Colaborador.php');
require_once(__DIR__ . '/../../../logica/Punto_recoleccion.php');
$punto = new Punto_recoleccion();
$puntos = $punto -> clasificar_by_categoria($_GET["categoria"]);
$ubicaciones = [];

foreach ($puntos as $temp) {
    $ubicaciones[] = [
        "nombre" => $temp->getNombre(),
        "direcion" => $temp->getDireccion(),
        "lat" => floatval($temp->getLatitud()),
        "lng" => floatval($temp->getLongitud()),
        "estado" => $temp->getEstado(),
        "colaborador" => $temp->getColaborador()->getNombre()
    ];
}
if(!$puntos) {
    echo "no hay ningun punto de asignacion";
    exit();
}
// Convertir a JSON para pasarlo a JavaScript
$ubicaciones_json = json_encode($ubicaciones);

include(__DIR__ . '/map.php'); //
?>