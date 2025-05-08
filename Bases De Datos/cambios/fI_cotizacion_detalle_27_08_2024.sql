ALTER TABLE
    `facturas_001`.`fi_cotizacion_detalle`
ADD
    COLUMN `impuesto_id` int NULL DEFAULT 0 COMMENT 'ID del impuesto'
AFTER
    `subtotal_2`;