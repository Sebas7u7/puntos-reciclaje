<?php
// Array de ubicaciones con nombre, latitud y longitud
$ubicaciones = [
    ["nombre" => "Bogotá", "lat" => 4.6097, "lng" => -74.0817],
    ["nombre" => "Medellín", "lat" => 6.2442, "lng" => -75.5812],
    ["nombre" => "Cali", "lat" => 3.4516, "lng" => -76.5320],
];

// Convertir a JSON para pasarlo a JavaScript
$ubicaciones_json = json_encode($ubicaciones);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Varias ubicaciones en Leaflet</title>
  <meta charset="utf-8" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <style>
    #map { height: 500px; width: 100%; }
  </style>
</head>
<body>

<h2>Mapa con múltiples ubicaciones</h2>
<div id="map"></div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
  // Recibir datos desde PHP
  const ubicaciones = <?php echo $ubicaciones_json; ?>;

  // Inicializar el mapa en una ubicación general (Bogotá por ejemplo)
  const map = L.map('map').setView([4.6097, -74.0817], 6);

  // Añadir capa de mapa base
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  // Recorrer las ubicaciones y agregarlas al mapa
  ubicaciones.forEach(function(ubicacion) {
    L.marker([ubicacion.lat, ubicacion.lng])
      .addTo(map)
      .bindPopup(ubicacion.nombre);
  });
</script>

</body>
</html>