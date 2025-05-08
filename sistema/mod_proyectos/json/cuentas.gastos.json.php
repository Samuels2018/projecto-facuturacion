<?php  


    if (!defined('ENLACE_SERVIDOR')) {  
        session_start(); 
        require_once("../../conf/conf.php");  
    } 

    //LA ENTIDAD DEL USUARIO EN SESION
    if(empty( $_SESSION['usuario']) or empty($_SESSION['Entidad']))
    {
        echo  acceso_invalido( ) ;
        exit(1);
    }


    // VALID DEFINITIO ACTION
    if (! empty($_POST['action'])):
        // VALID ACTION    
        switch($_POST['action']):
            case 'treeInfoLoadTypesExpenses':
                treeInfoLoadTypesExpenses($dbh);
            break;            
        endswitch;
    endif;
    
    // FUNCION LOAD INFO TYPES EXPESES
    function treeInfoLoadTypesExpenses($dbh){
        
        
        $array = array();
       
       array_push($array, array( 
                    "id"      => 0 ,
                    "parent"  => "#",
                    "text"    => "Cuentas Gastos",
                    "icon"    => "fa fa-folder"  ,
                    "state"   => array("opened" => false, 'selected' => false) 
                    )); 
         
                      
        $query2 = 
            "SELECT 
                rowid
                 
            FROM fi_gastos_tipos 
            WHERE entidad = :entidad 
            AND activo = 1 ORDER BY fk_parent;";
        $db2 = $dbh->prepare($query2);
        $db2->bindValue(':entidad', $_SESSION['Entidad'], PDO::PARAM_INT);
        $db2->execute();
        $array_valid = $db2 -> fetchAll(PDO::FETCH_ASSOC);

                        foreach($array_valid as $row) {
                           $category[] = $row['rowid'];
                        }
        //$subcategory = implode(',', $category);    
                    
                    
        $query = 
            "SELECT 
                rowid,
                nombre,
                entidad,
                fk_parent,
                require_cedula,
                activo
            FROM fi_gastos_tipos 
            WHERE entidad = :entidad 
            AND activo = 1 ORDER BY fk_parent;";
        $db = $dbh->prepare($query);
        $db->bindValue(':entidad', $_SESSION['Entidad'], PDO::PARAM_INT);
        $db->execute();
        
        //VALIDAR SI EL REGISTRO EL FK_PARENT EXITE EN EL ARRA ID.
       
        
        // RUN RECORDS
        while($data = $db->fetch(PDO::FETCH_OBJ)):
            
       
        
           
            // VALID FATHER
            if (($data->fk_parent) == 0 ){
                $father = "0";
            }else{
                $father = $data->fk_parent;   
            }
            $icon = "fa fa-folder";
            
            if( $data->fk_parent==0){
                 array_push($array, array( 
                            "id"      => $data->rowid,
                            "parent"  => $father,
                            "text"    => $data->nombre,
                            "icon"    => $icon,
                            "state"   => array("opened" => true, 'selected' => false)
                        ));
                
            }
            
           if((in_array($data->fk_parent, $category) ) ){
                  // ASIGNA LA INFORMACION AL ARREGLO
                  array_push($array, array( 
                            "id"      => $data->rowid,
                            "parent"  => $father,
                            "text"    => $data->nombre,
                            "icon"    => $icon,
                            "state"   => array("opened" => true, 'selected' => false)
                        ));
           }
       
        
        endwhile;
        echo json_encode($array);
         
    }       

?>
        
        
        