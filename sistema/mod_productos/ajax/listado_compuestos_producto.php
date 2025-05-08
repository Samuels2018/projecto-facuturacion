<?php

if (!defined('ENLACE_SERVIDOR')) {
  session_start();
  require_once("../../conf/conf.php");
}


if (!empty($_POST['cantidad']) and !empty($_POST['objeto']) and !empty($_GET['fiche'])) {

  $sql = "insert into 

        fi_productos_compuesto (fk_product_padre,fk_product_hijo,cantidad,gratis) values
        (:fk_product_padre, :fk_product_hijo, :cantidad ,:gratis) ";

  $db = $dbh->prepare($sql);
  $db->bindValue(':fk_product_padre', $_GET['fiche'], PDO::PARAM_INT);
  $db->bindValue(':fk_product_hijo', $_POST['objeto'], PDO::PARAM_INT);
  $db->bindValue(':cantidad', $_POST['cantidad'], PDO::PARAM_INT);
  $db->bindValue(':gratis', $_POST['gratis'], PDO::PARAM_INT);
  $db->execute();
}



if (!empty($_POST['relacion']) and !empty($_GET['fiche'])) {

  $sql = "Delete  from    fi_productos_compuesto   where md5(rowid) = :rowid";
  $db = $dbh->prepare($sql);
  $db->bindValue(':rowid', $_POST['relacion'], PDO::PARAM_STR);
  $db->execute();
}



?>


<div class="card-body">
  <div class="table-responsive">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Cantidad</th>
          <th>Precio</th>
          <th></th>
        </tr>
        </th>
      </thead>
      <tbody role="alert" aria-live="polite" aria-relevant="all">
        <?php
        ///  tpl para mostrar los compuestos

        $sql = "select
           pc.rowid               ,
           pc.cantidad            ,
           p.label                ,
           p.rowid as producto_id ,
           pc.gratis              ,
           (select total from  fi_productos_precios_clientes  where fk_producto  = p.rowid  order by rowid DESC limit 0 ,1 ) as precio
           from 
           fi_productos_compuesto pc 

           inner join  fi_productos p on  p.rowid    = pc.fk_product_hijo  

           where pc.fk_product_padre = " . $_GET['fiche'] . " 

           order by label ASC ";

        $db = $dbh->prepare($sql);
        $db->execute();
        $total_ = 0;

        while ($salarios = $db->fetch(PDO::FETCH_OBJ)) {




          if ($salarios->gratis == 1) {
            $tipo = '<span class="label label-warning">Gratis</span>';
          } else if ($salarios->gratis == 0) {
            $tipo = '<span class="label label-primary">' . numero($salarios->precio * $salarios->cantidad) . '</span>';
            $total_ += $salarios->precio * $salarios->cantidad;
          } else {
            $tipo = "Ocurrio Un Error En la Suma del precio";
          }

          echo '<tr><td><a href="' . ENLACE_WEB . 'dashboard.php?accion=productos_editar&fiche=' . $salarios->producto_id . '"><i class="fa fa-fw fa fa-bookmark-o"></i>' . $salarios->label . '</a></td>

  
    <td data-label="Nombre">' . $salarios->cantidad . '</td>
    <td data-label="Cantidad">' . $tipo . '</td>
    <td data-label="Precio" style="cursor:pointer;" onclick="confirma_eliminar_relacion(\'' . md5($salarios->rowid) . '\')">x</td>
    </tr>  ';
          $i++;
        }
        echo '<tr><Td colspan="2" align="right">Total</td><td>' . numero($total_) . '</td></tr>';
        ?>
    </table>
  </div>
</div>