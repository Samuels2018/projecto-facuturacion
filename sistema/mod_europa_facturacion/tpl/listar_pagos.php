<?php 

include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/object/documentos_pagos.object.php");

$Payment_sales = new DocumentosPagos($dbh, $_SESSION['Entidad']);
$PagosRealizados = $Payment_sales->getByInvoiceId($Documento->id);

if($Documento->estado == 0){
   return;
}

if (count($PagosRealizados)>0){ 

?>


   <div class="col-md-12">
      <div class="form-group">
         <h6 class="mb-0">Pagos Realizados a la Factura <?php echo $Documento->referencia; ?></h6>
         <div class="row">
            <table class="table table-bordered table-hover">
               <thead class="thead-light">
                  <tr>
                     <th>Pago</th>
                     <th>Fecha</th>
                     <th>Tipo</th>
                     <th>Monto</th>
                  </tr>
               </thead>
               <tbody>
                  <?php foreach ($PagosRealizados as $pago): ?>
                     <tr>
                        <td>  <?= $pago['rowid']  ?>  </td>
                        <td><?= date("d-m-Y", strtotime($pago['fecha_pago'])); ?></td>
                        <td><?= $pago['forma_pago'] == 1 ? 'Efectivo' : 'Tarjeta'; ?></td>
                        <td>
                           <span class="badge badge-danger">
                              <i class="fas fa-euro-sign"></i> <?= number_format($pago['monto'], 2); ?>
                           </span>
                        </td>
                     </tr>
                  <?php endforeach ?>
               </tbody>
            </table>
         </div>
      </div>
   </div>
<?php }  ?>