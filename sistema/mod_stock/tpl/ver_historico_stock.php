<?php

include(ENLACE_SERVIDOR . 'mod_stock/object/bodega.object.php');
include(ENLACE_SERVIDOR . 'mod_productos/object/productos.object.php');

$bodega_data  = new bodega($dbh);
$bodega_data->fetch($_GET['bodega']);



$productos = new Productos($dbh);
$productos->fetch($_GET['fiche']);



$TAMANO_PAGINA = $resultados_pagina;

$where = " and (fk_bodega = " . $_GET['bodega'] . ")  and  (fk_producto = " . $_GET['fiche'] . " )";

$pagina = $_GET["pagina"];
if (!$pagina) {
    $inicio = 0;
    $pagina = 1;
} else {
    $inicio = ($pagina - 1) * $TAMANO_PAGINA;
}



$nrows = $dbh->query("Select count(rowid)as total  from fi_bodegas_movimientos  where 1 $where  ")->fetchColumn();
$num_total_registros = $nrows;
$total_paginas = ceil($num_total_registros / $TAMANO_PAGINA);
$mostrando = "Mostrando la página " . $pagina . " de " . $total_paginas . "<p>";

$sql = "Select * from fi_bodegas_movimientos where 1 $where  order by rowid  DESC limit  " . $inicio . "," . $TAMANO_PAGINA;
$db = $dbh->prepare($sql);
$db->execute();


$contado = 0;
$class = "add";

while ($obj = $db->fetch(PDO::FETCH_ASSOC)) {


    if ($class == "add") {
        $class = "even";
    } else {
        $class = "add";
    }

    if ($obj['tipo'] == 0) {
        $obj['tipo'] = '<i class="fa fa-fw fa-step-forward"></i> Entrada';
    } else if ($obj['tipo'] == 1) {
        $obj['tipo'] = '<i class="fa fa-fw fa-backward"></i> Salida';
    } else {
        $obj['tipo'] = "- No definido-";
    }


    $tr .= "<tr class='$class' >
 <td> <i class=\"$tipo\"></i>" . $obj['rowid'] . " </a></td> <td>" . $obj['tipo'] . " </td><td align='center'>" . $obj['valor'] . "</td> <td>" . $obj['stock_actual'] . "</td><td> " . date('d-m-Y H:i', strtotime($obj['fecha'])) . "</td>
<td>" . $obj['motivo'] . " </td>
 </tr>";
}



?>

<div class="middle-content container-xxl p-0">
    <div class="row layout-top-spacing">
        <!-- BREADCRUMB -->
        <div class="page-meta">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo ENLACE_WEB; ?>productos_listado">Productos</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo !empty($_REQUEST['fiche']) ? 'Editar' : 'Nuevo' ?></li>
                </ol>
            </nav>
        </div>
        <!-- /BREADCRUMB -->
        <div class="col-md-12 mt-4">
            <section class="content">
                <div class="row my-3">
                    <div class="col-xs-6">
                        <a class="btn btn-info" href="<?php echo ENLACE_WEB; ?>dashboard.php?accion=productos_editar&fiche=<?php echo $productos->id; ?>&tab=stock">
                            </i>Registrar Entradas/Salidas <?php echo $productos->label; ?>
                        </a>
                    </div>

                </div>
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Movimientos del producto
                            <a href="<?php echo ENLACE_WEB; ?>dashboard.php?accion=productos_editar&fiche=<?php echo $productos->id; ?>&tab=stock">
                                <?php echo ucwords($productos->label); ?>
                            </a> en
                            <a href="<?php echo ENLACE_WEB; ?>dashboard.php?accion=bodega_listado&bodega=<?php echo $bodega_data->id; ?>" onclick="alert('Pertenece a bodegas, pendiente') ;return false"><b><?php echo  $bodega_data->label; ?></b>
                            </a>
                        </h6>
                    </div><!-- /.card-header -->
                    <div class="card-body">

                        <div class="table-responsive">

                            <table id="zero-config" class="table table-bordered" aria-describedby="example1_info">
                                <thead>
                                    <tr role="row">
                                        <th>Id</th>
                                        <th>Tipo</th>
                                        <th>Unidades Transladadas</th>
                                        <th>Stock Al Momento</th>
                                        <th>Fecha & Hora</th>
                                        <th>Motivo</th>
                                    </tr>
                                </thead>
                                <tbody role="alert" aria-live="polite" aria-relevant="all">

                                    <?php echo $tr;  ?>

                                </tbody>
                            </table>

                        </div>
                    </div><!-- /.card-body -->
                </div>


            </section>

        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        $('#zero-config').DataTable({
            "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
                "<'table-responsive'tr>" +
                "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
            "oLanguage": {
                "oPaginate": {
                    "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                    "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                },
                "sInfo": "Mostrando _PAGE_ de _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Search...",
                "sLengthMenu": "Results :  _MENU_",
            },
            "stripeClasses": [],
            "lengthMenu": [7, 10, 20, 50],
            "pageLength": 3
        });

    })
</script>