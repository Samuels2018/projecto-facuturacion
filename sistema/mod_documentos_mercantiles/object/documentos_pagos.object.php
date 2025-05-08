<?php

class DocumentosPagos
{
    private $db;
    private $entity;

    // Constructor
    public function __construct($db, $entity)
    {
        $this->db = $db;
        $this->entity = $entity;
    }

    /**
     * Create a new payment record.
     */
    public function create($data)
    {
        try {
            // Validar datos obligatorios
            if (empty($data['fk_documento']) || empty($data['forma_pago']) || empty($data['monto']) || empty($data['usuario']) || empty($data['fecha_pago']) || empty($data['tipo']) ) {
                return ['success' => false, 'message' => 'Faltan datos obligatorios'];
            }
    
            $this->db->beginTransaction(); // Iniciar transacción
    
            // Obtener el total pagado actual de la factura
            $sql = "SELECT fk_tercero, pagado, total FROM fi_europa_".$data['tipo']."s WHERE rowid = :fk_documento AND entidad = :entidad ";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':fk_documento', $data['fk_documento'], PDO::PARAM_INT);
            $stmt->bindValue(':entidad', $this->entity, PDO::PARAM_INT);
            $stmt->execute();
            $factura = $stmt->fetch(PDO::FETCH_ASSOC);
            // Obtener el total pagado actual de la factura

            // Obtener el cliente_default de la entidad
            $sql_config = "SELECT IFNULL(valor,0) as cliente_generico FROM fi_configuracion WHERE entidad = :entidad AND configuracion = 'cliente_defecto' AND activo = 1 AND borrado = 0";
            $stmt_config = $this->db->prepare($sql_config);
            $stmt_config->bindValue(':entidad', $this->entity, PDO::PARAM_INT);
            $stmt_config->execute();
            $_config = $stmt_config->fetch(PDO::FETCH_ASSOC);
            // Obtener el cliente_default de la entidad

            if (!$factura) {
                $this->db->rollBack(); // Revertir si la factura no existe
                return ['success' => false, 'message' => $data['tipo'].' no encontrada'];
            }
            if($factura['estado_pagada'] == 1 ){
                $this->db->rollBack();
                return ['success' => false, 'message' => $data['tipo'].' ya se encuentra pagada'];
            }
            if( $data['monto'] > ($factura['total'] - $factura['pagado']) ){
                $this->db->rollBack();
                return ['success' => false, 'message' => 'Monto mayor que el saldo'];
            }

            // Insertar el pago en la tabla de pagos
            $sql = "INSERT INTO fi_europa_documentos_pagos 
                (fk_documento, entidad, forma_pago, monto, comentario, usuario, fecha_registrado, fecha_pago, tipo) 
                VALUES 
                (:fk_documento, :entidad, :forma_pago, :monto, :comentario, :usuario, NOW(), :fecha_pago, :tipo)";
    
            $stmt = $this->db->prepare($sql);
    
            // Asignar valores
            $stmt->bindValue(':fk_documento', $data['fk_documento'], PDO::PARAM_INT);
            $stmt->bindValue(':entidad', $this->entity, PDO::PARAM_INT);
            $stmt->bindValue(':forma_pago', $data['forma_pago'], PDO::PARAM_INT);
            $stmt->bindValue(':monto', $data['monto'], PDO::PARAM_STR);
            $stmt->bindValue(':comentario', $data['comentario'], PDO::PARAM_STR);
            $stmt->bindValue(':usuario', $data['usuario'], PDO::PARAM_INT);
            $stmt->bindValue(':fecha_pago', $data['fecha_pago'], PDO::PARAM_STR);
            $stmt->bindValue(':tipo', $data['tipo'], PDO::PARAM_STR);
    
            if (!$stmt->execute()) {
                $errorInfo = $stmt->errorInfo();
                $this->db->rollBack(); // Revertir si falla
                return [
                    'success' => false,
                    'message' => 'No se pudo insertar el pago',
                    'error_info' => $errorInfo[2] ?? 'Error desconocido'
                ];
            }
    
            $nuevoTotalPagado = $factura['pagado'] + $data['monto'];
            $estadoPagada = ($nuevoTotalPagado >= $factura['total']) ? 1 : 0;
    
            // Actualizar el monto pagado y el estado de la factura
            $sql = "UPDATE fi_europa_".$data['tipo']."s 
                    SET pagado = :pagado, estado_pagada = :estado_pagada 
                    WHERE rowid = :fk_documento AND entidad = :entidad";
    
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':pagado', $nuevoTotalPagado, PDO::PARAM_STR);
            $stmt->bindValue(':estado_pagada', $estadoPagada, PDO::PARAM_INT);
            $stmt->bindValue(':fk_documento', $data['fk_documento'], PDO::PARAM_INT);
            $stmt->bindValue(':entidad', $this->entity, PDO::PARAM_INT);
    
            if (!$stmt->execute()) {
                $errorInfo = $stmt->errorInfo();
                $this->db->rollBack(); // Revertir si falla
                return [
                    'success' => false,
                    'message' => 'No se pudo actualizar la '.$data['tipo'],
                    'error_info' => $errorInfo[2] ?? 'Error desconocido'
                ];
            }

            // Actualizar el saldo_credito del Cliente de la Factura
            // Si el tercero es igual al cliente genérico no se actualiza stock
            if ( $factura['fk_tercero'] != $_config["cliente_generico"] ){
                $sql_tercero = "UPDATE fi_terceros SET saldo_credito = (saldo_credito + :monto_pagado ) WHERE rowid = :fk_tercero AND entidad = :entidad";
                $stmt_tercero = $this->db->prepare($sql_tercero);
                $stmt_tercero->bindValue(':monto_pagado', $data['monto'], PDO::PARAM_STR);
                $stmt_tercero->bindValue(':fk_tercero', $factura['fk_tercero'], PDO::PARAM_INT);
                $stmt_tercero->bindValue(':entidad', $this->entity, PDO::PARAM_INT);
                $stmt_tercero->execute();
                if(!$stmt_tercero){
                    $this->sql     =   $sql_tercero;
                    $this->error   =   implode(", ", $dbh->errorInfo());
                    $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
                    $this->Error_SQL();
                }
            }
            // Actualizar el saldo_credito del Cliente de la Factura
    
            $this->db->commit(); // Confirmar transacción
    
            return ['success' => true, 'id' => $this->db->lastInsertId()];
        } catch (Exception $e) {
            $this->db->rollBack(); // Revertir en caso de excepción
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    

    /**
     * Get payments for a specific invoice.
     */
    public function getByInvoiceId($invoiceId, $tipo='factura')
    {
        try {
            $sql = "SELECT * FROM fi_europa_documentos_pagos WHERE fk_documento = :fk_documento AND entidad = :entidad and tipo=:tipo";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':fk_documento', $invoiceId, PDO::PARAM_INT);
            $stmt->bindValue(':entidad', $this->entity, PDO::PARAM_INT);
            $stmt->bindValue(':tipo', $tipo, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Get total payments for a specific invoice.
     */
    public function getTotalPaidByInvoiceId($invoiceId, $tipo='factura')
    {
        try {
            $sql = "SELECT SUM(monto) AS total_paid FROM fi_europa_documentos_pagos 
                    WHERE fk_documento = :fk_documento AND entidad = :entidad and tipo=:tipo";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':fk_documento', $invoiceId, PDO::PARAM_INT);
            $stmt->bindValue(':entidad', $this->entity, PDO::PARAM_INT);
            $stmt->bindValue(':tipo', $tipo, PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['total_paid'] : 0;
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Delete a payment record.
     */
    public function delete($id, $tipo='factura')
    {
        try {
            $sql = "DELETE FROM fi_europa_documentos_pagos WHERE rowid = :rowid AND entidad = :entidad AND tipo= :tipo";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':rowid', $id, PDO::PARAM_INT);
            $stmt->bindValue(':entidad', $this->entity, PDO::PARAM_INT);
            $stmt->bindValue(':tipo', $tipo, PDO::PARAM_STR);
            $stmt->execute();

            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
