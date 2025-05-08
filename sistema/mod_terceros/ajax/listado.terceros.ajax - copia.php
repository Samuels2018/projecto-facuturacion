<?php
if (!defined('ENLACE_SERVIDOR')) {
    SESSION_START();
    require_once "../../conf/conf.php";

    $where = '';

    $pagina_actual = $_POST['pagina'];
} else {

    $pagina_actual = 0;
}

$paginacion;
if (isset($_POST['pagina'])) {
    if ($_POST['pagina'] == 0) {
        $paginacion;
    } else {
        $pagina     = $_POST['pagina'] * 20;
        $paginacion = 'OFFSET ' . $pagina;
    }
}

// hacer esto con cada columna para los filtros
if (!empty($_REQUEST['rowid'])) {
    $where .= ' and ft.rowid = "' . $_REQUEST['rowid'] . '"';
}
if (!empty($_REQUEST['nombre'])) {
    $where .= ' and ft.nombre like  "%' . $_REQUEST['nombre'] . '%"';
}
if (!empty($_REQUEST['cedula'])) {
    $where .= ' and ft.cedula like  "%' . $_REQUEST['cedula'] . '%"';
}
if (!empty($_REQUEST['telefono'])) {
    $where .= ' and ft.telefono like  "%' . $_REQUEST['telefono'] . '%"';
}
if (!empty($_REQUEST['email'])) {
    $where .= ' and ft.email like  "%' . $_REQUEST['email'] . '%"';
}
if (!empty($_REQUEST['tipo'])) {
    $where .= ' and ft.tipo = "' . $_REQUEST['tipo'] . '"';
}
if (!empty($_REQUEST['activo'])) {
    $where .= ' and ft.activo = "' . $_REQUEST['activo'] . '"';
}

// VALID ORDER
$order = (!is_null($_REQUEST['order'])) ? 'ft.nombre ' . strtoupper($_REQUEST['order']) : 'ft.nombre ASC';

// ROWS
$nrows = $dbh->query("SELECT COUNT(rowid) as total FROM fi_terceros ft WHERE 1 AND ft.entidad = " . $_SESSION['Entidad'] . "  AND activo = 1 $where ORDER BY $order;")->fetchColumn();
$num_total_registros = $nrows;
$cuentaTerceros    = $num_total_registros / 20;

if ($cuentaTerceros < 1) {
    $paginasTerceros = 1;
} else {
    $paginasTerceros = ceil($cuentaTerceros);
}

// QUERY
$sql = "
    Select
    ft.*
    from fi_terceros ft  
    where 1   
    and  ft.entidad = " . $_SESSION['Entidad'] . "   
    and ft.activo=1  $where ORDER BY $order LIMIT 20 " . $paginacion;
$db = $dbh->prepare($sql);
$db->execute();

$contado = 0;
$class   = "add";

while ($obj = $db->fetch(PDO::FETCH_ASSOC)) {

    if ($class == "add") {
        $class = "even";
    } else {
        $class = "add";
    }

    //-------------------
    $contado += $obj['valor'];

    $tr .= "<tr>

<td data-label='ID' >
   
        <a href='" . ENLACE_WEB . "terceros_editar/" . $obj['rowid'] . "'>
                     " . $obj['rowid'] . " </a>
    
</td>

<td data-label='Nombre'>" . $obj['nombre'] . "</td>

<td data-label='Cedula' >" . $obj['cedula'] . "</td>

<td data-label='Telefono' >" . $obj['telefono'] . "</td>

<td data-label='Email' >" . $obj['email'] . "</td>

<td data-label='Tipo Persona' >" . $obj['tipo'] . "</td>

<td data-label='Estatus' >" . $obj['activo'] . "</td>

</tr>";
} // fin del while

echo $tr;

$iii=0;

