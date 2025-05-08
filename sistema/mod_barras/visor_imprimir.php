<?php

session_start();

require_once("../conf/conf.php");

$sql = "select p.* ,
       (select total  from fi_productos_precios_clientes where fk_producto = p.rowid order by rowid DESC limit 0,1) as precio
       from fi_productos p where p.rowid = :rowid ";
$db = $dbh->prepare($sql);
$db->bindValue(':rowid', $_GET['producto'], PDO::PARAM_INT);
$db->execute();
$obj = $db->fetch(PDO::FETCH_OBJ);

?>

<!DOCTYPE html>
<html lang=es>
<meta charset="UTF-8" />
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
    width: 100%;
    height: 0.75cm;
    background-color: transparent;
    border: 0px solid #73AD21;
  }

  div.absolute1 {
    position: absolute;
    top: 1.5cm;
    left: 0.07cm;
    width: 100%;
    height: 3cm;
    font-size: medium;
    background-color: transparent;
    border: 0px solid #e01818;
  }

  div.absolute2 {
    position: absolute;
    top: 3.8cm;
    left: 0.07cm;
    width: 100%;
    height: 2cm;
    font-size: xx-large;
    background-color: transparent;
    border: 0px solid #73AD21;
  }
</style>

<head>
</head>

<body>
  <div id="borde_etiquetas" class="relative">

    <div id="nombre_producto" class="absolute">
      <p><em><?php echo $obj->label; ?> </em>
      <p>
    </div>

    <div id="codigo_barras" class="absolute1" align="center">

      <img src="sample-gd.php?producto=<?php echo $obj->rowid; ?>" style="padding-top: 0;" OnLoad="window.print();" />
      <p style="width: 100%; padding: 0; margin: 0;"><?php echo $obj->codigo_barras; ?></p>
    </div>

    <div id="precio" class="absolute2" align="center">
      <p> <?php echo number_format($obj->precio, 2, ",", "."); ?>
      <p>
    </div>

  </div>
</body>

</html>