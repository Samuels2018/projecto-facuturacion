<?php

    //----------------------------------------------------------------------------
    //
    //      David Bermejo     
    //      Julio 2020 - Peñaranda
    //      Creado para guardar las notas de 1 forma mas eficiente 
    //
    //          Validamos primero cada parametro para poder controlar de la forma mas eficiente los errores
    //          Parte de las mejoras de Usabilidad 
    // 
    
    
   SESSION_START();
   if (empty($_SESSION['Entidad']) ){
        $person = array( 
            "error" => 1, 
            "txt"   => "Sesion No iniciada"
        ); 
        echo json_encode($person);//returns JSON string
        exit(1);    
   }
   
   
   
   
   
   if (empty($_REQUEST['factura'])){
       
        $person = array( 
            "error" => 1, 
            "txt"   => "Parametro factura no encontrado"
        ); 
        echo json_encode($person);//returns JSON string
        exit(1);  
   }
   
   
   
   
    if (empty($_REQUEST['tipo'])) {
       
        $person = array( 
            "error" => 1, 
            "txt"   => "Parametro TIPO  no encontrado"
        ); 
        echo json_encode($person);//returns JSON string
        exit(1);  
   }
   
   
   
   
    if ($_REQUEST['tipo']=="detalle" or $_REQUEST['tipo']=="notageneral" ) {
       
        include("../../conf/conf.php");
        
        $campo = $_REQUEST['tipo'];
        
        $sql = "update  fi_europa_facturas  set   $campo = :valor where rowid = :rowid  and entidad = :entidad ";
        $db  = $dbh->prepare($sql);
        $db->bindValue(":valor"  , $_REQUEST['valor']         , PDO::PARAM_STR);
        $db->bindValue(":rowid"  , $_REQUEST['factura']       , PDO::PARAM_INT);
        $db->bindValue(":entidad", $_SESSION['Entidad']   , PDO::PARAM_STR);
        $ejecution = $db->execute();
        $a=$db->rowCount();
    
        $person = array( 
            "error" => ($ejecucion==0 and $a = 0 ) ? 1 : 0,   // No Existe le error 
            "txt"   => ($ejecucion==0 and $a > 0 ) ? "Documento No Afectado" : "Documento Actualizado"
        ); 
        echo json_encode($person);//returns JSON string
        exit(1); 
        
        
        
    } else{
        $person = array( 
            "error" => 1, 
            "txt"   => "Parametro TIPO  no Esperado"
        ); 
        echo json_encode($person);//returns JSON string
        exit(1);  
   }
   
   
   
   $sql = "";