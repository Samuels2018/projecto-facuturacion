<?php


class kit_digital extends  Seguridad
{

    function  __construct($db){

        $this->db=$db; 
        parent::__construct();  // Esto inicializa la clase SEGURIDAD
    } 


   
    // Creación de empresa desde el objeto kit digital
    public function insertarEmpresa()
    {
        // SQL query for inserting a new company
        $sql = "
            INSERT INTO sistema_empresa (
                nombre,
                nombre_comercial,
                direccion_fk_provincia,
                direccion_fk_municipio,
                telefono_fijo,
                telefono_movil,
                website,
                fk_estado,
                creado_fecha,
                creado_fk_usuario,
                kit_aplica_kit_digital,
                kit_fk_tipo,
                kit_pdf_firmado,
                kit_pdf_firmado_url_en_disco,
                kit_direccion_completa,
                kit_codigo_postal,
                vendedor_fk_usuario,
                tipo,
                cedula,
                notas_empresa
            ) VALUES (
                :nombre,
                :nombre_comercial,
                :direccion_fk_provincia,
                :direccion_fk_municipio,
                :telefono_fijo,
                :telefono_movil,
                :website,
                :fk_estado,
                NOW(),
                :creado_fk_usuario,
                :kit_aplica_kit_digital,
                :kit_fk_tipo,
                :kit_pdf_firmado,
                :kit_pdf_firmado_url_en_disco,
                :kit_direccion_completa,
                :kit_codigo_postal,
                :vendedor_fk_usuario,
                :tipo,
                :cedula,
                :notas_empresa
            )
        ";

        // Prepare the SQL statement
        $stmt = $this->db->prepare($sql);

        // Bind values to the SQL statement
        $stmt->bindValue(':nombre', $this->nombre);
        $stmt->bindValue(':nombre_comercial', $this->nombre_comercial);
        $stmt->bindValue(':direccion_fk_provincia', $this->direccion_fk_provincia);
        $stmt->bindValue(':direccion_fk_municipio', $this->direccion_fk_municipio);
        $stmt->bindValue(':telefono_fijo', $this->telefono_fijo);
        $stmt->bindValue(':telefono_movil', $this->telefono_movil);
        $stmt->bindValue(':website', $this->website);
        $stmt->bindValue(':fk_estado', $this->fk_estado);
        $stmt->bindValue(':creado_fk_usuario', $this->creado_fk_usuario);
        $stmt->bindValue(':kit_aplica_kit_digital', $this->kit_aplica_kit_digital);
        $stmt->bindValue(':kit_fk_tipo', $this->kit_fk_tipo);
        $stmt->bindValue(':kit_pdf_firmado', $this->kit_pdf_firmado);
        $stmt->bindValue(':kit_pdf_firmado_url_en_disco', $this->kit_pdf_firmado_url_en_disco);
        $stmt->bindValue(':kit_direccion_completa', $this->kit_direccion_completa);
        $stmt->bindValue(':kit_codigo_postal', $this->kit_codigo_postal);
        $stmt->bindValue(':vendedor_fk_usuario', $this->vendedor_fk_usuario);
        
        // New fields
        $stmt->bindValue(':tipo', $this->tipo);
        $stmt->bindValue(':cedula', $this->cedula);
        $stmt->bindValue(':notas_empresa', $this->notas_empresa);
        


        // Execute the statement and check for success
        if ($stmt->execute()) {
            return [
                'exito' => 1,
                'mensaje' => 'Empresa creada correctamente',
                'id' => $this->db->lastInsertId()
            ];
        } else {
            $this->sql = $sql;
            $this->error = implode(", ", $stmt->errorInfo());
            $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
            $this->Error_SQL();

            return [
                'exito' => 0,
                'mensaje' => $stmt->errorInfo()
            ];
        }
    }   

    public function actualizarEmpresa()
    {
        // SQL query for updating an existing company
        $sql = "
            UPDATE sistema_empresa SET 
                nombre = :nombre,
                nombre_comercial = :nombre_comercial,
                direccion_fk_provincia = :direccion_fk_provincia,
                direccion_fk_municipio = :direccion_fk_municipio,
                telefono_fijo = :telefono_fijo,
                telefono_movil = :telefono_movil,
                website = :website,
                kit_aplica_kit_digital = :kit_aplica_kit_digital,
                kit_fk_tipo = :kit_fk_tipo,
                kit_pdf_firmado = :kit_pdf_firmado,
                kit_pdf_firmado_url_en_disco = :kit_pdf_firmado_url_en_disco,
                kit_direccion_completa = :kit_direccion_completa,
                kit_codigo_postal = :kit_codigo_postal,
                vendedor_fk_usuario = :vendedor_fk_usuario,
                tipo = :tipo,
                cedula = :cedula,
                notas_empresa = :notas_empresa
            WHERE rowid = :id
        ";

        // Prepare the SQL statement
        $stmt = $this->db->prepare($sql);

        // Bind values to the SQL statement
        $stmt->bindValue(':nombre', $this->nombre);
        $stmt->bindValue(':nombre_comercial', $this->nombre_comercial);
        $stmt->bindValue(':direccion_fk_provincia', $this->direccion_fk_provincia);
        $stmt->bindValue(':direccion_fk_municipio', $this->direccion_fk_municipio);
        $stmt->bindValue(':telefono_fijo', $this->telefono_fijo);
        $stmt->bindValue(':telefono_movil', $this->telefono_movil);
        $stmt->bindValue(':website', $this->website);
        $stmt->bindValue(':kit_aplica_kit_digital', $this->kit_aplica_kit_digital);
        $stmt->bindValue(':kit_fk_tipo', $this->kit_fk_tipo);
        $stmt->bindValue(':kit_pdf_firmado', $this->kit_pdf_firmado);
        $stmt->bindValue(':kit_pdf_firmado_url_en_disco', $this->kit_pdf_firmado_url_en_disco);
        $stmt->bindValue(':kit_direccion_completa', $this->kit_direccion_completa);
        $stmt->bindValue(':kit_codigo_postal', $this->kit_codigo_postal);
        $stmt->bindValue(':vendedor_fk_usuario', $this->vendedor_fk_usuario);
        
        // New fields
        $stmt->bindValue(':tipo', $this->tipo);
        $stmt->bindValue(':cedula', $this->cedula);
        $stmt->bindValue(':notas_empresa', $this->notas_empresa);
        
        $stmt->bindValue(':id', $this->id);

        // Execute the statement and check for success
        if ($stmt->execute()) {
            return [
                'exito' => 1,
                'mensaje' => 'Empresa actualizada correctamente',
                'id' => $this->id
            ];
        } else {
            $this->sql = $sql;
            $this->error = implode(", ", $stmt->errorInfo());
            $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
            $this->Error_SQL();

            return [
                'exito' => 0,
                'mensaje' => $stmt->errorInfo()
            ];
        }
    }





public function actualizarKitDigital()
{
    // SQL query to update an existing company's Kit Digital information
    $sql = "
        UPDATE sistema_empresa SET 
            kit_aplica_kit_digital = :kit_aplica_kit_digital,
            kit_fk_tipo = :kit_fk_tipo,
            kit_pdf_firmado = :kit_pdf_firmado,
            kit_pdf_firmado_url_en_disco = :kit_pdf_firmado_url_en_disco,
            kit_monto_aprobado = :kit_monto_aprobado,
            fk_kit_digital_estado = :fk_kit_digital_estado
        WHERE rowid = :rowid
    ";

    $stmt = $this->db->prepare($sql);

    // Bind values to parameters
    $stmt->bindValue(':kit_aplica_kit_digital', $this->kit_aplica_kit_digital);
    $stmt->bindValue(':kit_fk_tipo', $this->kit_fk_tipo);
    $stmt->bindValue(':kit_pdf_firmado', $this->kit_pdf_firmado);
    $stmt->bindValue(':kit_pdf_firmado_url_en_disco', $this->kit_pdf_firmado_url_en_disco);
    $stmt->bindValue(':kit_monto_aprobado', $this->kit_monto_aprobado);
    $stmt->bindValue(':fk_kit_digital_estado', $this->fk_kit_digital_estado);
    $stmt->bindValue(':rowid', $this->rowid); // The unique identifier for the company

    // Execute the update and check for success
    if ($stmt->execute()) {
        return [
            'exito' => 1,
            'mensaje' => 'Kit Digital actualizado correctamente',
            'id' => $this->rowid
        ];
    } else {
        // Capture error information if execution fails
        $this->sql = $sql;
        $this->error = implode(", ", $stmt->errorInfo());
        $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
        $this->Error_SQL();

        return [
            'exito' => 0,
            'mensaje' => $stmt->errorInfo()
        ];
    }
}


public function actualizarComisiones()
{
    // SQL query to update commission fields
    $sql = "
        UPDATE sistema_empresa
        SET 
            kit_monto_comision = :kit_monto_comision,
            kit_monto_comision_pagada = :kit_monto_comision_pagada,
            kit_factura_emitida = :kit_factura_emitida,
            kit_factura_emitida_fecha = :kit_factura_emitida_fecha,
            kit_factura_emitida_pagada = :kit_factura_emitida_pagada
        WHERE rowid = :rowid
    ";

    // Prepare the SQL statement
    $stmt = $this->db->prepare($sql);

    // Bind values to the parameters
    $stmt->bindValue(':kit_monto_comision', $this->kit_monto_comision);
    $stmt->bindValue(':kit_monto_comision_pagada', $this->kit_monto_comision_pagada);
    $stmt->bindValue(':kit_factura_emitida', $this->kit_factura_emitida);
    $stmt->bindValue(':kit_factura_emitida_fecha', $this->kit_factura_emitida_fecha);
    $stmt->bindValue(':kit_factura_emitida_pagada', $this->kit_factura_emitida_pagada);
    $stmt->bindValue(':rowid', $this->rowid);

    // Execute and check for success
    if ($stmt->execute()) {
        return [
            'exito' => 1,
            'mensaje' => 'Comisiones actualizadas correctamente'
        ];
    } else {
        // Capture error information in case of failure
        return [
            'exito' => 0,
            'mensaje' => 'Error al actualizar comisiones: ' . implode(", ", $stmt->errorInfo())
        ];
    }
}



public function fetchAllKitDigitalTipo()
{
    $sql = "
        SELECT rowid, etiqueta 
        FROM diccionario_kit_digital_tipo
    ";

    $stmt = $this->db->prepare($sql);

    // Execute the query
    if ($stmt->execute()) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all results as associative array
    } else {
        // Capture and return error information if execution fails
        $this->sql = $sql;
        $this->error = implode(", ", $stmt->errorInfo());
        $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
        $this->Error_SQL();

        return [
            'exito' => 0,
            'mensaje' => 'Error al recuperar los tipos de Kit Digital',
            'error_info' => $stmt->errorInfo()
        ];
    }
}


    public function listar_estados_kitdigital()
    {
        

        $sql = "
            SELECT * FROM diccionario_kit_digital_estado
        ";

        $stmt = $this->db->prepare($sql);

        // Execute the query
        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all results as associative array
        } else {
            // Capture and return error information if execution fails
            $this->sql = $sql;
            $this->error = implode(", ", $stmt->errorInfo());
            $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
            $this->Error_SQL();

            return [
                'exito' => 0,
                'mensaje' => 'Error al recuperar los tipos de Kit Digital',
                'error_info' => $stmt->errorInfo()
            ];
        }
    }

}


?>