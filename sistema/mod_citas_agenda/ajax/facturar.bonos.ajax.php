<?php
// Función para generar una fecha aleatoria en el año 2024
function generarFechaAleatoria2024() {
    $inicio = strtotime("2024-01-01");
    $fin = strtotime("2024-12-31");
    $fechaAleatoria = mt_rand($inicio, $fin);
    return date("d-m-Y", $fechaAleatoria);
}

// Fecha actual
$hoy = date("Y-m-d");

// Generar registros aleatorios
$registros = [];
for ($i = 1; $i <= 10; $i++) {
    $fecha = generarFechaAleatoria2024();
    $esPasada = $fecha < $hoy ? "✔️" : "";
    $registros[] = [
        "numero" => $i,
        "fecha" => $fecha,
        "check" => $esPasada
    ];
}
?>
 
 <div class="row mt-5">                  
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table-bordered">
        <thead>
            <tr>
                <th>Número Consecutivo</th>
                <th>Fecha Visita</th>
                <th>Realizado </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registros as $registro): ?>
                <tr>
                    <td><?= $registro["numero"] ?></td>
                    <td><?= $registro["fecha"] ?></td>
                    <td><?= $registro["check"] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>                    
    </div>
</div>


<span OnClick="datos_general()">Volver a Datos de la Cita </span>
 
         
 