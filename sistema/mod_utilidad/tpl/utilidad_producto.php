<div class="card">
                <div class="card-header">
                  <i class="fa fa-fw fa-bar-chart-o"></i> Calculo Utilidad  
                </div><!-- /.card-header -->
                <div class="card-body no-padding">
                
<?php

$sql="
Select 
(select subtotal  from fi_productos_precios_clientes where fk_producto = :rowid2 order by rowid DESC  limit 0,1) as venta ,
(select precio from fi_productos_precios_costo    where fk_producto = :rowid2 order by rowid DESC  limit 0,1) as compra,
(select impuesto from fi_productos_precios_costo    where fk_producto = :rowid2 order by rowid DESC  limit 0,1) as impuesto,
(select SUM(stock) from fi_bodegas_stock  where fk_producto = :rowid2 group by  fk_producto) as cantidad 
 ";
 
 $db=$dbh->prepare($sql);
 $db->bindValue(':rowid2', $_POST['fiche'] ,PDO::PARAM_INT);

 $db->execute();
 $datos_=$db->fetch(PDO::FETCH_OBJ);

 if(!empty($datos_)){


 if ($datos_->impuesto=="E") { }
  else {  /* El costo incluye el impuesto! */ 

    $resultado = (($datos_->compra* 100));
    if ($resultad0 > 0) {
      $datos_->compra = $resultado/113;
    }
  

   

        }


$utilidad=$datos_->venta-$datos_->compra;
if( ($datos_->venta*100) > 0 ){
  
   $compra =$datos_->compra;
  if($compra == 0) $compra = 1;
  $porcentaje = (($datos_->venta*100)/$compra );

  $porcentaje =  $porcentaje - 100 ;
}


}
 
?>

<?php if (!empty($datos_)):  ?>
<table class="table table-bordered" >
<tr><Td>Precio Costo </td><td><?php echo numero_decimal($datos_->compra);  ?></td></tr>
<tr><Td>Precio Venta </td><td><?php echo numero_decimal($datos_->venta);  ?></td></tr>
<tr><Td>Precio Utilidad </td><td><?php echo  numero_decimal($utilidad);  ?>( <?php echo number_format($porcentaje, 2, ",", ".") ; ?> %)</td></tr>
<tr><Td>Utilidad  del 
<a href="<?php echo ENLACE_WEB; ?>dashboard.php?accion=productos_stock&fiche=<?php echo $_GET['fiche']; ?>">
 Stock (<?php echo $datos_->cantidad; ?>) Actual en Bodega </td><td><?php echo numero($utilidad * $datos_->cantidad) ;  ?>
</a>
</td></tr>

</table>                </div>
</div>
<?php endif; ?>
