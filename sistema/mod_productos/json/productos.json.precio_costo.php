<?php
header('Content-Type: text/html; charset=utf-8');
   session_start();
   require('../../conf/conf.php'); 
   
   
   
   $term = trim(strip_tags($_GET['term'])); 
   
   if ($_REQUEST['tipo']=="productos" ){  $filtro=" and tipo  = 1 ";}
    else if ($_REQUEST['tipo']=="servicios" ){  $filtro=" and tipo  = 0 ";}
      else {} 
   
   
   
   
   
   if ($_SESSION['licencia']=="eccbc87e4b5ce2fe28308fd9f2a7baf3" and $_SESSION['Entidad']==1){


 $Where_akua=' and borrado_logico = 0 ';
} 


   $sql="Select 
   p.rowid , 
   p.ref   , 
   p.label   from fi_productos  p  where    (  (CONCAT(ref,' ',label) LIKE '%".$term."%' )  or (p.codigo_barras = '".$term."') )    and  entidad = ".$_SESSION['Entidad']."     $Where_akua       limit 0,10    "; 

   $db=$dbh->prepare($sql);
   $db->execute();

                     
					     

     while($obj=$db->fetch(PDO::FETCH_ASSOC)){
	

   $sql="    
   select 
   impuesto ,
   precio as total   from fi_productos_precios_costo where fk_producto = ".$obj['rowid']." order by  rowid DESC limit 0,1 	  ";



    $db2=$dbh->prepare($sql);
    $db2->execute();
    $precios=$db2->fetch(PDO::FETCH_ASSOC);


        if ($precios['impuesto']=="E"){ 


            $impuesto =  0;
            $subtotal = $precios['total']; 
            $total    = $precios['total']; 


         }  else {

         


            $restarle = ($precios['total']*100)/113;

                
            $total    = $precios['total']; 
            $subtotal = $restarle;
            $impuesto =  13;
         
         }    


	$row['value']=strtolower($obj['label']);
	$row['id']=(int)$obj['rowid'];
	$row['impuesto']=(int)$impuesto;
	$row['subtotal']=(int)$subtotal;
	$row['total']=(int)$total;
   $row['ref']=$obj['ref'];


	$row_set[] = $row; 
									 
	}


echo json_encode($row_set);

?>