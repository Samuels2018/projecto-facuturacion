<?php
 /**
  * 
 OPERACIONAL 
  DROP TABLE `diccionario_campos_extra_tipo`;
DROP TABLE `diccionario_formularios`;
  DROP TABLE `campos_extra_detalle`;

CREATE TABLE `campos_extra_detalle` (
	`fk_campo_extra_formulario` INT(10) NOT NULL,
	`fk_documento` INT(10) NOT NULL,
	`entidad` INT(10) NOT NULL,
	`valor` VARCHAR(100) NOT NULL COLLATE 'latin1_swedish_ci',
	`activo` VARCHAR(2) NOT NULL DEFAULT '1' COMMENT '1 - activo / 0 - inactivo' COLLATE 'latin1_swedish_ci',
	UNIQUE INDEX `Índice 2` (`fk_campo_extra_formulario`, `fk_documento`) USING BTREE
)
COLLATE='latin1_swedish_ci'
ENGINE=MyISAM
ROW_FORMAT=DYNAMIC
;





CREATE TABLE `campos_extra_formularios` (
	`rowid` INT(10) NOT NULL AUTO_INCREMENT,
	`entidad` INT(10) NOT NULL,
 	`fk_diccionario_campos_extra_tipo` INT(10) NULL DEFAULT NULL,
	`input_etiqueta` VARCHAR(200) NOT NULL COLLATE 'utf8mb4_general_ci',
	`input_descripcion` VARCHAR(200) NOT NULL COLLATE 'utf8mb4_general_ci',
	`input_obligatorio` INT(10) NOT NULL DEFAULT '0',
	`input_valor_defecto` VARCHAR(200) NOT NULL COLLATE 'utf8mb4_general_ci',
	`parametros` VARCHAR(200) NULL DEFAULT NULL COMMENT 'rows en textarea y opciones en seleccion\r\n[\r\n  { "value": "opcion1", "label": "Opción 1" },\r\n  { "value": "opcion2", "label": "Opción 2" },\r\n  { "value": "opcion3", "label": "Opción 3" }\r\n]' COLLATE 'utf8mb4_general_ci',
	`fi_europa_albaranes_compras` INT(10) NULL DEFAULT '0',
	`fi_europa_compras` INT(10) NULL DEFAULT '0',
	`fi_europa_albarenes_ventas` INT(10) NULL DEFAULT '0',
	`fi_europa_pedidos` INT(10) NULL DEFAULT '0',
	`fi_europa_presupuestos` INT(10) NULL DEFAULT '0',
	`fi_europa_facturas` INT(10) NULL DEFAULT '0',
	`parametros_ancho` SET('3','6','12') NULL DEFAULT '12' COMMENT 'La suma total de una linea es 12' COLLATE 'utf8mb4_general_ci',
	`orden` INT(10) NOT NULL DEFAULT '0',
	PRIMARY KEY (`rowid`) USING BTREE,
	INDEX `entidad` (`entidad`) USING BTREE,
	INDEX `fk_diccionario_campos_extra_tipo` (`fk_diccionario_campos_extra_tipo`) USING BTREE 
 )
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
ROW_FORMAT=DYNAMIC
AUTO_INCREMENT=6
;



------------------------------------------------
--Utilidades
-------------------------------------------------
DROP TABLE `diccionario_formularios`;
DROP TABLE `diccionario_campos_extra_tipo`;

   CREATE TABLE `diccionario_campos_extra_tipo` (
	`rowid` INT(10) NOT NULL AUTO_INCREMENT,
	`etiqueta` VARCHAR(300) NOT NULL COLLATE 'utf8mb4_general_ci',
	`input` VARCHAR(10) NOT NULL COLLATE 'utf8mb4_general_ci',
	`descripcion` VARCHAR(300) NOT NULL COLLATE 'utf8mb4_general_ci',
	`activo` INT(10) NULL DEFAULT '0' COMMENT 'Si esta en Uso el caso de Uso o  todavia no',
	PRIMARY KEY (`rowid`) USING BTREE
)
COMMENT='Campos Extras'
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
ROW_FORMAT=DYNAMIC
AUTO_INCREMENT=10
AVG_ROW_LENGTH=1820
;



 INSERT INTO `diccionario_campos_extra_tipo` (`rowid`, `etiqueta`, `input`, `descripcion`, `activo`) VALUES
	(1, 'Campo de Texto', 'text', 'Un campo de entrada de texto', 1),
	(2, 'Área de Texto', 'textarea', 'Un área de texto más grande para entradas de varias líneas', 1),
	(3, 'Selección', 'select', 'Un campo de selección desplegable', 0),
	(4, 'Casilla de Verificación', 'checkbox', 'Un campo de casilla de verificación', 0),
	(5, 'Botón de Opción', 'radio', 'Un campo de botón de opción', 0),
	(6, 'Fecha', 'date', 'Un campo para seleccionar una fecha', 1),
	(7, 'Número', 'number', 'Un campo de entrada para números', 1),
	(8, 'Correo Electrónico', 'email', 'Un campo de entrada para direcciones de correo electrónico', 1),
	(9, 'Contraseña', 'password', 'Un campo de entrada para contraseñas', 1);

    

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

 
class Extra extends  Seguridad
{
    public  $db;
    public  $fk_documento;


    public function __construct($db, $entidad)
    {
        $this->db       = $db;
        $this->entidad  = $entidad;

        parent::__construct($db, $entidad);  // Esto inicializa la clase SEGURIDAD

    }


    /***************************************************
     * 
     * 
     * 
     *      Lectura de Formulario Dinamico 
     * 
     * 
     * 
     *********************************************************/

    public function Generar_Formulario($fk_formulario, $id)
    {

        $filtro_documento = '';
        if($id>0){
            $filtro_documento = " and ced.fk_documento = :rowid ";
        }        

        $sql = "
        SELECT 
            cef.parametros  , 
            cef.parametros_ancho ,
            cef.rowid as ID ,
            cef.input_etiqueta,
            cef.input_descripcion,
            cef.input_obligatorio,
            cef.input_valor_defecto,
            dce.input AS tipo_campo ,
            ced.valor
        FROM campos_extra_formularios cef

        JOIN " . DB_NAME_UTILIDADES_APOYO . ".diccionario_campos_extra_tipo dce ON cef.fk_diccionario_campos_extra_tipo = dce.rowid
        
        LEFT JOIN campos_extra_detalle ced 
                    ON ced.fk_campo_extra_formulario = cef.rowid 
                    AND ced.entidad = :entidad
                    AND ced.activo = '1'
                    {$filtro_documento}
        WHERE 

            cef.{$fk_formulario} = 1  -- Esto permite el ser multi Documentos y heredar el dato de un documento a otro! 
        AND cef.entidad       = :entidad 
        
        order by cef.orden ASC ";

        $db = $this->db->prepare($sql);
        $db->bindValue(":fk_formulario" , $fk_formulario        , PDO::PARAM_INT);
        $db->bindValue(":entidad"       , $_SESSION['Entidad']  , PDO::PARAM_INT);
        if($id>0){
            $db->bindValue(":rowid" , $id        , PDO::PARAM_INT);
        }
        $db->execute();
        $campos = $db->fetchAll(PDO::FETCH_ASSOC);

     
        foreach ($campos as $campo) {


            $this->datos[$campo['ID'] ]['input_etiqueta']       = $campo['input_etiqueta'];
            $this->datos[$campo['ID'] ]['valor']                = $campo['valor'];
            $this->datos[$campo['ID'] ]['input_valor_defecto']  = $campo['input_valor_defecto'];
            


            $html .= '<div class="form-group col-md-'.$campo['parametros_ancho'].' mt-3">';
            $html .= '<label  for=""  >' . htmlspecialchars($campo['input_etiqueta']) . '</label>';


            $required = $campo['input_obligatorio'] ? 'required="required" ' : '';
            $default_value = (!empty($campo['valor'])) ? $campo['valor'] : htmlspecialchars($campo['input_valor_defecto']);
            
            switch ($campo['tipo_campo']) {
                        
                        case 'email':
                            $html.='<div class="input-group has-validation">
                                            <span class="input-group-text" id="inputGroupPrepend">@</span>
                                            <input  type="'.$campo['tipo_campo'].'"  id="input-' . htmlspecialchars($campo['ID']) . '" name="input-' . htmlspecialchars($campo['ID']) . '" value="' . $default_value . '" ' . $required . '  aria-describedby="inputGroupPrepend" class="form-control form-control-sm "  >
                                            <div class="invalid-feedback">
                                              Please choose a username.
                                            </div>
                                          </div>
                                        ';

                            break;
                        case 'text':
                        case 'password':
                        case 'number':
                        case 'date':

                            $html .= '<input type="'.$campo['tipo_campo'].'"  id="input-' . htmlspecialchars($campo['ID']) . '" name="input-' . htmlspecialchars($campo['ID']) . '" value="' . $default_value . '" ' . $required . ' class="form-control form-control-sm ">';
                break;


                 
              
                    
                case 'textarea':
                    $html .= '<textarea  ' . ($campo['parametros']) . '  id="input-' . htmlspecialchars($campo['ID']) . '" name="input-' . htmlspecialchars($campo['ID']) . '"  '.$required . ' class="form-control">' . $default_value . '</textarea>';
                break;


                case 'select':
                    $html .= '<select id="' . htmlspecialchars($campo['input_etiqueta']) . '" name="' . htmlspecialchars($campo['input_etiqueta']) . '" ' . $required . ' class="form-control">';
                    $html .= '<option value="">Seleccione una opción</option>';
                    
                    $options = json_decode($campo['parametros'], true);

                    foreach ($options as $option): 
                            $selected= ( $default_value== $option['value'] ) ? "selected='selected'":"";
                            $html.="<option value='{$option['value']}'  {$selected} >
                                    ".htmlspecialchars($option['label'])."
                                </option>";
                    endforeach; 
                    
                    $html .= '</select>';
                    break;


                /*case 'checkbox':
                    $html .= '<input type="checkbox" id="' . htmlspecialchars($campo['input_etiqueta']) . '" name="' . htmlspecialchars($campo['input_etiqueta']) . '" ' . $required . ' class="form-check-input">';
                    break;
                case 'radio':
                    $html .= '<input type="radio" id="' . htmlspecialchars($campo['input_etiqueta']) . '" name="' . htmlspecialchars($campo['input_etiqueta']) . '" ' . $required . ' class="form-check-input">';
                    break; */
                default:
                    $html .= 'ERROR'; //<input type="text" id="' . htmlspecialchars($campo['input_etiqueta']) . '" name="' . htmlspecialchars($campo['input_etiqueta']) . '" value="' . $default_value . '" ' . $required . ' class="form-control">';
            }

            $html .= '<small class="form-text text-muted">' . htmlspecialchars($campo['input_descripcion']) . '</small> </div>';
        }

        $this->html = $html;
        return $html;
    }


    public function obtener_campos_extras($tabla_documento){

        $sql = "
        SELECT 
            cef.parametros  , 
            cef.parametros_ancho ,
            cef.rowid as ID ,
            cef.input_etiqueta,
            cef.input_descripcion,
            cef.input_obligatorio,
            cef.input_valor_defecto
        FROM campos_extra_formularios cef
        JOIN " . DB_NAME_UTILIDADES_APOYO . ".diccionario_campos_extra_tipo dce ON cef.fk_diccionario_campos_extra_tipo = dce.rowid
        WHERE 
            cef.{$tabla_documento} = 1
        AND cef.entidad       = :entidad         
        order by cef.orden ASC ";

        $db = $this->db->prepare($sql);
        $db->bindValue(":entidad"       , $_SESSION['Entidad']  , PDO::PARAM_INT);
        $db->execute();
        $campos = $db->fetchAll(PDO::FETCH_ASSOC);
     
        return $campos;
    }

    /************************************************
     * 
     * 
     *   Guardar Formulario
     * 
     * 
     ***********************************************/

     public function guardar_Respuesta($fk_campo_extra_formulario, $valor ){
        $sql= "
        INSERT INTO campos_extra_detalle  ( fk_campo_extra_formulario,fk_documento , entidad , valor , activo)
                    VALUES ( :fk_campo_extra_formulario,:fk_documento ,:entidad, :valor , 1)
                    ON DUPLICATE KEY UPDATE
                    valor = :valor;

        ";


        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':entidad'                      ,   $this->entidad              , PDO::PARAM_STR);
        $dbh->bindValue(':fk_campo_extra_formulario'    ,   $fk_campo_extra_formulario  , PDO::PARAM_INT);
        $dbh->bindValue(':valor'                        ,   $valor                      , PDO::PARAM_STR);
        $dbh->bindValue(':fk_documento'                 ,   $this->fk_documento         , PDO::PARAM_INT);
         
      
        $a = $dbh->execute();


        if (!$a) {
            $this->sql     =   $sql;
           echo  $this->error   =   implode(", ", $dbh->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla campos_extra_detalle  " ;
            $this->Error_SQL();
        }
        



     }
}

