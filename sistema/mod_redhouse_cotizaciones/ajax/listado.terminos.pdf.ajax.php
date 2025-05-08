<?php
/* Lista de Terceros o Cotizaciones */ 
/*----------------------------------------------------*/ 
session_start();
require_once "../../conf/conf.php";

if (isset($_GET['start']) && isset($_GET['length']))
{
    // Si están presentes, utiliza los valores proporcionados
    $inicio = $_GET['start'];
    $limite = $_GET['length'];
} else {
    // Si no están presentes, establece valores predeterminados
    $inicio = 0; // Por ejemplo, iniciar desde el primer registro
    $limite = 10; // Por ejemplo, mostrar 10 registros por página
}
$buscarArray = $_GET['search'];
$buscar = $buscarArray['value'];
$usuario_cotizacion = $_GET['usuario_cotizacion'];

if ($buscar == '') {
    $totalRecords = 0;
    $sqlstr = "SELECT COUNT(*) AS TotalREg FROM a_medida_cisma_cotizaciones_PDF WHERE fk_cotizacion = $usuario_cotizacion";
    $dataB = $dbh->prepare($sqlstr);
    $dataB->execute();
    $Records = $dataB->fetchAll();
    foreach ($Records as $row) {
        $totalRecords = $row['TotalREg'];
    }

    $sqlstr = "SELECT 
            a.rowid,
            a.titulo,
            a.texto,
            a.orden,
            a.activo,
            a.creado_fecha,
            a.fk_machote_pdf,
            (SELECT nombre FROM fi_usuarios WHERE rowid = a.creado_fk_usuario) AS nombre,
            (SELECT apellidos FROM fi_usuarios WHERE rowid = a.creado_fk_usuario) AS apellido
        FROM 
            a_medida_cisma_cotizaciones_PDF a
        WHERE 
            a.fk_cotizacion = $usuario_cotizacion
        ORDER BY a.orden ASC  LIMIT $inicio, $limite";

    $dataB = $dbh->prepare($sqlstr);
    $dataB->execute();
    $Records = $dataB->fetchAll();
    $data = array();
    foreach ($Records as $row) {

        $texto_corto = substr(strip_tags($row['texto']), 0, 20); // Obtener los primeros 100 caracteres del texto sin etiquetas HTML
        // Si el texto original es más largo que el extracto corto, agrega puntos suspensivos
        if (strlen(strip_tags($row['texto'])) > 20) {
            $texto_corto .= '...';
        } 

        $nombre_usuario = '';
        if($row['nombre']!=NULL)
        {
            $nombre_usuario = $row['nombre'].' '.$row['apellido'];
        }

        $data[] = array(
            "ID" => $row['rowid'],
            "Titulo" => $row['titulo'],
            "Texto" =>$texto_corto,
            "texto_html" =>$row['texto'],
            "modificacion" =>$row['creado_fecha'],
            "Activo" => $row['activo'],
            "usuario" => $nombre_usuario,
            "fk_machote_pdf" => $row['fk_machote_pdf']
        );
    }
    $response = array(
        "draw" => intval($_GET['draw']),   
        "recordsTotal" => intval($totalRecords),  
        "recordsFiltered" => intval($totalRecords),
        "data" => $data
    );
} else {
    $totalRecords = 0;
    $sqlstr2 = "SELECT 
            a.rowid,
            a.titulo,
            a.texto,
            a.orden,
            a.activo,
            a.creado_fecha,
            a.fk_machote_pdf,
            (SELECT nombre FROM fi_usuarios WHERE rowid = a.creado_fk_usuario) AS nombre,
            (SELECT apellidos FROM fi_usuarios WHERE rowid = a.creado_fk_usuario) AS apellido
        FROM 
            a_medida_cisma_cotizaciones_PDF a
        WHERE 
            a.fk_cotizacion = $usuario_cotizacion ";

    $sqlstr2 .= " AND (
                        a.titulo LIKE '%$buscar%'
                        OR a.texto LIKE '%$buscar%'
                    )";
    ;
    $sqlstr3 = $sqlstr2 ." ORDER BY a.orden ASC LIMIT $inicio, $limite";

    $dataB = $dbh->prepare($sqlstr3);
    $dataB->execute();
    $Records = $dataB->fetchAll();
    $data = array();
    foreach ($Records as $row)
    {
        $texto_corto = substr(strip_tags($row['texto']), 0, 20); // Obtener los primeros 100 caracteres del texto sin etiquetas HTML
        // Si el texto original es más largo que el extracto corto, agrega puntos suspensivos
        if (strlen(strip_tags($row['texto'])) > 20) {
            $texto_corto .= '...';
        }
        $nombre_usuario = '';
        if($row['nombre']!=NULL)
        {
            $nombre_usuario = $row['nombre'].' '.$row['apellidos'];
        }

        $data[] = array(
            "ID" => $row['rowid'],
            "Titulo" => $row['titulo'],
            "Texto" => $texto_corto,
            "texto_html" =>$row['texto'],
            "modificacion" =>$row['creado_fecha'],
            "Activo" => $row['activo'],
            "usuario" => $nombre_usuario,
            "fk_machote_pdf" => $row['fk_machote_pdf']

        );
        $totalRecords = $totalRecords + 1;
    }
    $response = array(
        "draw" => intval($_GET['draw']),
        "recordsTotal" => intval($totalRecords),
        "recordsFiltered" => intval($totalRecords),
        "data" => $data
    );
}
echo json_encode($response);
?>
