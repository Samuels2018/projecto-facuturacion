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
        
        case 'eliminar':
            eliminar($dbh, $_POST);
        break;
        
        case 'select':
            select($dbh, $_POST);
        break;
    }
    
    
    
    
    function agregar($dbh, $datos){
        $sql = "INSERT INTO `fi_productos_ubicacion`( `entidad`, `fk_producto`, `fk_sucursal`, `ubicacion`, `ubicacion_2`, `fecha`, `activo`) 
                VALUES (:entidad,:producto,:sucursal,:ubicacion,:ubicacion_2,now(),1)";
        $db = $dbh->prepare($sql);
        $db->bindValue(':producto', $datos['producto'] , PDO::PARAM_INT);
        $db->bindValue(':entidad', $_SESSION['Entidad'] , PDO::PARAM_INT);
        $db->bindValue(':sucursal', 1 , PDO::PARAM_INT);
        $db->bindValue(':ubicacion', $datos['ubicacion'] , PDO::PARAM_INT);
        $db->bindValue(':ubicacion_2', $datos['ubicacion_2'] , PDO::PARAM_INT);
        $db->execute();
        print_r($dbh->errorInfo());
        print_r($db->errorInfo());
        select($dbh, $datos);
    }

    
    function select($dbh, $datos){
        global $_SESSION;
        $sql = "SELECT
                sucur.label,
                dicc.etiqueta,
                dicc_2.etiqueta AS etiqueta_2
            FROM
                `fi_productos_ubicacion` AS ubi
            INNER JOIN fi_sucursal AS sucur
            ON
                sucur.rowid = ubi.fk_sucursal
            INNER JOIN fi_productos_ubicacion_diccionario AS dicc
            ON
                dicc.rowid = ubi.ubicacion
            INNER JOIN fi_productos_ubicacion_diccionario AS dicc_2
            ON
                dicc_2.rowid = ubi.ubicacion_2
            WHERE
                ubi.entidad = :entidad AND 
                ubi.fk_producto = :producto AND 
                ubi.fk_sucursal = 1 AND 
                ubi.activo = 1
            ORDER BY
                ubi.rowid
            DESC
            LIMIT 1
        ";
        $db = $dbh->prepare($sql);
        $db->bindValue(':entidad', $_SESSION['Entidad'], PDO::PARAM_INT);
        $db->bindValue(':producto', $datos['producto'], PDO::PARAM_INT);
        $db->execute();
        if($db->rowCount() > 0){
            while($ubicacion = $db->fetch(PDO::FETCH_OBJ)){
                $trUbi .=   "
                            <tr>
                                <tr>
                                    <th>Ubicacion</th>
                                </tr>
                                <td>
                                    ".$ubicacion->etiqueta."
                                </td>
                                <tr>
                                    <th>Ubicacion 2</th>
                                </tr>
                                <td>
                                    ".$ubicacion->etiqueta_2."
                                </td>
                            </tr>
                            ";
            }
            echo $trUbi;
        }else{
            $trUbi .=   "
                        <tr>
                            <td colspan='6' align='center'>
                                No hay datos configurados para este producto
                            </td>
                        </tr>
                        ";
            echo $trUbi;
        }
    }
    
    
    
    
    
    
    
?>