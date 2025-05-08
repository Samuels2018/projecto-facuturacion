<?php
//header('Content-Type: text/html; charset=utf-8');
   session_start();
   require('../../conf/conf.php'); 
   
   
   
   $term = trim(strip_tags($_GET['term'])); 

   $sql="Select rowid, nombre, apellidos    from fi_terceros_crm_contactos where fk_tercero = ".$_GET['fk_tercero']." AND
    (CONCAT(nombre,' ',apellidos) LIKE '%".$term."%')  limit 0,10  ";
   $db=$dbh->prepare($sql);
   $db->execute();
   
     while($obj=$db->fetch(PDO::FETCH_ASSOC)){
	
	$row['value']=(''.ucwords(strtolower($obj['nombre']))." ".ucwords(strtolower($obj['apellidos'])));
	$row['id']=(int)$obj['rowid'];
	$row_set[] = $row; 
									 
	}


echo json_encode($row_set);

?>