<?php
    session_start();
    include '../../conf/conf.php';
    
    switch($_POST['accion']){
        
        case 'insertar':
            agregar($dbh, $_POST);
        break;
        
        case 'editar':
            editar($dbh, $_POST);
        break;
        
        case 'inactivar':
            inactivar($dbh, $_POST);
        break;
        
        case 'select':
            select($dbh);
        break;
    }
    
    
    
    
    function agregar($dbh, $datos){
        var_dump($datos);
        $sql = "INSERT INTO `fi_productos_ubicacion_diccionario`(`entidad`, `etiqueta`, `activo`) VALUES (:entidad, :etiqueta, 1)";
        $db = $dbh->prepare($sql);
        $db->bindValue(':etiqueta', $datos['etiqueta'] , PDO::PARAM_INT);
        $db->bindValue(':entidad', $_SESSION['Entidad'] , PDO::PARAM_INT);
        $db->execute();
        select($dbh);
    }
    
    function editar($dbh, $datos){
        $sql = "UPDATE `fi_productos_ubicacion_diccionario` SET 
                `etiqueta` = :etiqueta,
                `activo` = :activo 
                WHERE 
                rowid = :rowid";
        $db = $dbh->prepare($sql);
        $db->bindValue(':etiqueta', $datos['etiqueta'], PDO::PARAM_INT);
        $db->bindValue(':activo',    $datos['activo'],       PDO::PARAM_INT);
        $db->bindValue(':rowid',    $datos['id'],       PDO::PARAM_INT);
        $db->execute();
    }
    
    function inactivar($dbh, $datos){
        
    }
    
    function select($dbh){
        global $_SESSION;
        $sql = "SELECT * FROM `fi_productos_ubicacion_diccionario` WHERE entidad = :entidad";
        $db = $dbh->prepare($sql);
        $db->bindValue(':entidad', $_SESSION['Entidad'], PDO::PARAM_INT);
        $db->execute();
        if($db->rowCount() > 0){
            $tr .= "
                    <tr>
                        <th>Etiqueta</th>
                        <th>Activo</th>
                        <th><Editar></th>
                    </tr>
                    ";
        while($lineas =  $db->fetch(PDO::FETCH_OBJ)){
                    if($lineas->activo == 1){
                        $opciones = "<option value='1' selected>Si</option>
                                     <option value='0'>No</option>
                                    ";
                    }else{
                        $opciones = "<option value='0' selected>No</option>
                                     <option value='1'>Si</option>
                                     ";
                    }
            $tr .= "
                <tr>
                    <td style='width:70%;'><input class='form-control' type='text' id='etiqueta_".$lineas->rowid."' value='".$lineas->etiqueta."'></td>
                    <td style='width:20%;'>
                        <select class='form-control' id='activo_".$lineas->rowid."'>
                            ".$opciones."
                        </select>
                    </td>
                    <td align='center'>
                        <div  class='btn btn-success' id='guardarCambios(".$lineas->rowid.")'><i class='fa fa-floppy-o'></i></div>
                    </td>
                </tr>
            ";
        }
        
        //echo "<table style='width:100%;'>";
        echo $tr;
        //echo "</table>";
    
        }else{
            echo "Sin Datos...";
            
            
        }    
    }
    
    
    
    
    
    
    
?>