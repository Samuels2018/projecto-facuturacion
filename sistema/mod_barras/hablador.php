<?php

 session_start();
 
 require_once("../conf/conf.php");
 
   $sql="select p.* ,
       (select total  from fi_productos_precios_clientes where fk_producto = p.rowid order by rowid DESC limit 0,1) as precio
       from fi_productos p where p.rowid = :rowid ";
 $db=$dbh->prepare($sql);
 $db->bindValue(':rowid', $_GET['producto'],PDO::PARAM_INT);
 $db->execute();
 $obj=$db->fetch(PDO::FETCH_OBJ);

   
 
?>
<html lang=es>
  <meta charset="utf-8" />
  <title> Formato de etiquetas </title>
  <style type="text/css">
    em {
        font-weight: bold;
        font-size: large;
        position: relative;
        top: -0.24cm;
    }

    body {
        width: 7.6cm;
        height: 3.1cm;
        border: 0px solid #73AD21;
    }

    div.relative {
        position: relative;
        width: 7.6cm;
        height: 3.1cm;
        background-color: transparent;
        border: 0px solid #1a0a7a;
    }

    div.absolute {
        position: absolute;
        top: 0.13cm;
        left: 0.16cm;
        width: 7.0cm;
        height: 0.75cm;
        background-color: transparent;
        border: 0px solid #73AD21;
    }

    div.absolute1 {
        position: absolute;
        top: 1.2cm;
        left: 0.07cm;
        width: 2.8cm;
        height: 2cm;
        font-size: medium;
        background-color: transparent;
        border: 0px solid #e01818;
    }

    div.absolute2 {
        position: absolute;
        top: 0.8cm;
        left: 3.6cm;
        width: 3.2cm;
        height: 1cm;
        font-size: xx-large;
        background-color: transparent;
        border: 0px solid #73AD21;
    }
  </style>

  <head>
  </head>

  <body OnLoad="window.print();" >
    <div id="borde_etiquetas" class="relative">

      <div id="nombre_producto" class="absolute">
        <p><em><?php echo $obj->descripcion; ?></em><p>
      </div>

      <div id="codigo_barras" class="absolute1">
        <!-- <img src="logo.jpg" /> -->
        <p><?php echo $obj->codigo_barras; ?></p>
      </div>

      <div id="precio" class="absolute2">
        <p> <?php echo number_format($obj->precio, 2, ",", "."); ?><p>
      </div>

    </div>
  </body>
</html>


